<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
#doc
#	classname:	upgrade
#	scope:		PUBLIC
#	StartBBS起点轻量开源社区系统
#	author :doudou QQ:858292510 startbbs@126.com
#	Copyright (c) 2013 http://www.startbbs.com All rights reserved.
#/doc

class Upgrade extends Other_Controller
{
	function __construct ()
	{
		parent::__construct();

	}
	public function index ()
	{
		echo "<font color=red>升级前务必要备份数据库！！！</font></br>升级版本从1.2.2到1.2.3</br>";
		echo "<a class='btn btn-default' href='".site_url('upgrade/do_upgrade')."'>开始升级</a>";
		
	}

	public function do_upgrade()
	{
		$dbprefix=$this->db->dbprefix;
		$database=$this->db->database;
		//删除多余的回复
		
		$topics=$this->db->select('topic_id')->get('topics')->result_array();
		$topic_ids1 = array_column($topics, 'topic_id');
		$comments1=$this->db->select('topic_id')->get('comments')->result_array();
		$topic_ids2 = array_column($comments1, 'topic_id');
		$topic_ids=array_diff($topic_ids2,$topic_ids1);
		if($topic_ids){
			$this->db->where_in('topic_id',$topic_ids)->delete('comments');
			echo '删除多余的回复1<br/>';
		}


		$users=$this->db->select('uid')->get('users')->result_array();
		$uids1 = array_column($users, 'uid');
		$comments2=$this->db->select('uid')->get('comments')->result_array();
		$uids2 = array_column($comments2, 'uid');
		$uids3=array_diff($uids2,$uids1);

		if($uids3){
			$this->db->where_in('uid',$uids3)->delete('comments');
			echo '删除多余的回复2<br/>';
		}

		
		$topics=$this->db->select('uid')->get('topics')->result_array();
		$uids4 = array_column($topics, 'uid');
		$uids5=array_diff($uids4,$uids1);

		if($uids5){
			$this->db->where_in('uid',$uids5)->delete('topics');
			echo '删除多余的话题<br/>';
		}


		$favorites=$this->db->select('uid')->get('favorites')->result_array();
		$uids6 = array_column($favorites, 'uid');
		$uids7=array_diff($uids6,$uids1);


		if($uids7){
			$this->db->where_in('uid',$uids7)->delete('favorites');
			echo '删除多余的收藏<br/>';
		}

		$follow=$this->db->select('uid')->get('user_follow')->result_array();
		$uids8 = array_column($follow, 'uid');
		$uids9=array_diff($uids8,$uids1);
		if($uids9){
			$this->db->where_in('uid',$uids9)->delete('user_follow');
			echo '删除多余的关注<br/>';
		}
		$notifications=$this->db->select('nuid')->get('notifications')->result_array();
		$uids10 = array_column($notifications, 'nuid');
		$uids11=array_diff($uids10,$uids1);
		if($uids11){
			$this->db->where_in('nuid',$uids11)->delete('notifications');
			echo '删除多余的通知<br/>';
		}

		$sql1="ALTER TABLE `{$dbprefix}user_groups` CHANGE `usernum` `usernum` INT( 11 ) NULL DEFAULT '0'";
		if($this->db->query($sql1)){
			echo "修改表user_groups成功<br/>";
		}
		sleep(1);
		$sql2="DROP TABLE IF EXISTS `{$dbprefix}site_stats`;";
		if($this->db->query($sql2)){
			echo "预删除表site_stats<br/>";
		}
		sleep(1);
		$sql3="CREATE TABLE IF NOT EXISTS `{$dbprefix}site_stats` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `item` varchar(20) NOT NULL,
  `value` int(10) DEFAULT '0',
  `update_time` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9;";
		if($this->db->query($sql3)){
			echo "增加表site_stats<br/>";
		}
		
		
		sleep(1);
		$total_users=$this->db->count_all_results('users');
		$total_topics=$this->db->count_all_results('topics');
		$total_comments=$this->db->count_all_results('comments');
		$total_nodes=$this->db->count_all_results('nodes');
		$total_tags=$this->db->count_all_results('tags');
		$today_topics=$this->db->where('TO_DAYS("addtime")','TO_DAYS(now())')->count_all_results('topics');

		$today_comments=$this->db->where('TO_DAYS(replytime)','TO_DAYS(now())')->count_all_results('comments');
		$topics=$today_topics+$today_comments;
		
		$last_uid=$this->db->select_max('uid')->get('users')->row_array();
		$sql4="INSERT INTO `{$dbprefix}site_stats` (`id`, `item`, `value`, `update_time`) VALUES
(1, 'last_uid', {$last_uid['uid']}, NULL),
(2, 'total_users', {$total_users}, NULL),
(3, 'today_topics', {$topics}, NULL),
(4, 'yesterday_topics', 0, NULL),
(5, 'total_topics', {$total_topics}, NULL),
(6, 'total_comments', {$total_comments}, NULL),
(7, 'total_nodes', {$total_nodes}, NULL),
(8, 'total_tags', {$total_tags}, NULL);";
		if($this->db->query($sql4)){
			echo "插入数据site_stats<br/>";
		}
		sleep(1);
		$sql5="ALTER TABLE `{$dbprefix}message_dialog` ADD `sender_read` TINYINT( 1 ) NOT NULL DEFAULT '1' AFTER `receiver_remove` , ADD `receiver_read` TINYINT( 1 ) NOT NULL DEFAULT '0' AFTER `sender_read`";
		if($this->db->query($sql5)){
			echo "增加字段sender_read<br/>";
		}
		sleep(1);
		$sql6="UPDATE `{$database}`.`{$dbprefix}message_dialog` SET `sender_read`='1',`receiver_read`='1'";
		if($this->db->query($sql6)){
			echo "更新数据message_dialog<br/>";
		}
		
		sleep(1);
		$sql7="ALTER TABLE `{$dbprefix}users` ADD `messages_unread` INT( 11 ) NULL DEFAULT '0' AFTER `favorites`";
		if($this->db->query($sql7)){
			echo "增加字段messages_unread<br/>";
		}
		sleep(1);
		$sql8="UPDATE `{$database}`.`{$dbprefix}users` SET `messages_unread` = '0'";
		if($this->db->query($sql8)){
			echo "更新数据users<br/>";
		}
		sleep(1);
		$path=FCPATH.'/app/controllers/upgrade.php';
		if(@unlink($path)){
			echo "成功删除upgrade.php<br/>";
		}
		sleep(1);
		echo "升级完成!!";

	}

}