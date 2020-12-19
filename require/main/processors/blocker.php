<?php
class Blocker
{
    public static function GetBlock($blocked_id, $by_id)
    {
        global $db;
        return database('SINGLE', 'QUERY', 'FETCH', sprintf("SELECT * FROM `user_blocks` WHERE `blocked_id` = '%s' AND `by_id` = '%s'", $db->real_escape_string($blocked_id), $db->real_escape_string($by_id) ));
    }

    public static function GetBlockByID($block_id)
    {
        global $db;
        return database('SINGLE', 'QUERY', 'FETCH', sprintf("SELECT * FROM `user_blocks` WHERE `id` = '%s'", $db->real_escape_string($block_id)));
    }

    public static function CreateBlock($blocked_id, $by_id)
    {
        global $db;

        if($blocked_id == $by_id) return false;

        $db->query(sprintf(
            "INSERT INTO `user_blocks` (`id`, `blocked_id`, `by_id`, `time`)
                VALUES
            (null, '%s', '%s', CURRENT_TIMESTAMP)",
            $db->real_escape_string($blocked_id),
            $db->real_escape_string($by_id)
        ));

        return Blocker::GetBlockByID($db->insert_id);
    }

    public static function RemoveBlock($block_id)
    {
        global $db;
        $db->query(sprintf(
            "DELETE FROM `user_blocks` WHERE `id` = '%s'",
            $db->real_escape_string($block_id)
        ));
    }
    
    public static function IsTypeBlocked($block, $type)
    {
        return ($block && $block[$type]) ? true : false;
    }

    public static function UpdateBlock($blocked_id, $by_id, $block_types)
    {
        global $db, $profile;

        if($blocked_id == $by_id) return false;

        $block = Blocker::GetBlock($blocked_id, $by_id);

        // create new block if doesnt exists
        if(!$block) $block = Blocker::CreateBlock($blocked_id, $by_id);

        // if requested remove the block
        if(!$block_types) return Blocker::RemoveBlock($block['id']);

        $query = sprintf(
            "UPDATE `user_blocks` 
                SET `follow` = '%s', 
                 `chat` = '%s', 
                 `search` = '%s', 
                 `groups` = '%s', 
                 `page_invite` = '%s', 
                 `profile` = '%s' 
                WHERE `id` = '%s'
                ",
            $db->real_escape_string($block_types['follow']),
            $db->real_escape_string($block_types['chat']),
            $db->real_escape_string($block_types['search']),
            $db->real_escape_string($block_types['groups']),
            $db->real_escape_string($block_types['page_invite']),
            $db->real_escape_string($block_types['profile']),
            $db->real_escape_string($block['id'])
        );

        $db->query($query);

        if($block_types['follow'])
            $profile->unFollowUser($by_id, $profile->getUserByID($blocked_id));
    }

    public static function BlockedUsers($from = 0, $by_user)
    {
        global $db, $profile, $TEXT;

        $limit = 6;

        $start = ($from && $from > 0) ? "AND `user_blocks`.`id` < '".$db->real_escape_string($from)."'" : "";

        $query = sprintf(
            "SELECT * FROM `user_blocks`, `users` 
            WHERE `users`.`idu` = `user_blocks`.`blocked_id` 
            AND `user_blocks`.`by_id` = '%s' $start ORDER BY `user_blocks`.`id` DESC LIMIT %s",
            $db->real_escape_string($by_user['idu']),
            $db->real_escape_string($limit)
        );

        $result = $db->query($query);

        if($result && $result->num_rows)
        {
            $rows = array();
            $people = "";

            while($row = $result->fetch_assoc())
                $rows[] = $row;

            $loadmore = (array_key_exists($limit - 1, $rows)) ? array_pop($rows) : false;

            $usr_tpl = display(templateSrc('SRC',1).'/block/user'.$TEXT['templates_extension'],0,1);

            foreach($rows as $row) 
            {
				$TEXT['temp-user_id'] = $row['idu'];
				$TEXT['temp-user_image'] = $row['image'];
				$TEXT['temp-user_ttl'] = sprintf($TEXT['_uni-Profile_load_text2'],fixName(32,$row['username'],$row['first_name'],$row['last_name']));
				$TEXT['temp-user_name_25'] = fixName(25,$row['username'],$row['first_name'],$row['last_name']);
                $TEXT['temp-user_verified_batch'] = $profile->verifiedBatch($row['verified'],1);
                $last = $row['id'];
				$people .= display('',$usr_tpl);
            }
            
            $people .= ($loadmore) ? addLoadmore($profile->settings['inf_scroll'],$TEXT['_uni-Load_more'],'loadMoreBlockedUsers('.$last.');') : closeBox($TEXT['_uni-No_More_Blocked_users']); 

            return $people;
        }
        else
        {
            return closeBox($TEXT['_uni-No_Blocked_Users_Yet']);
        }
    }
}