<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Notify extends Cron
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function all()
	{
		$this->cli->clear_screen();
		$this->cli->write('Grabbing notifications for this hour ('.date('G').')');
		
		$this->db->select('notify.*, notify.id AS notify_id')
				 ->select('movies.*')
				 ->select('releases.*')
			//	 ->select('(SELECT value FROM users_meta WHERE users_meta.key=\'notify_start\' AND user_id=notify.user_id) AS notify_start', FALSE)
			//	 ->select('(SELECT value FROM users_meta WHERE users_meta.key=\'notify_end\' AND user_id=notify.user_id) AS notify_end', FALSE);
				 ->select('((SELECT value FROM users_meta WHERE users_meta.key=\'notify_end\' AND user_id=notify.user_id) - HOUR(NOW())) as notify_order', FALSE);
				 
		$this->db->join('movies', 'notify.movie_id = movies.id')
				 ->join('releases', 'movies.id = releases.movie_id', 'inner');
		
		$this->db->where('notify.id NOT IN (SELECT notify_id FROM notifications)', NULL, FALSE)
				 ->where('releases.date BETWEEN CURDATE() AND DATE(DATE_ADD(CURDATE(), INTERVAL 7 DAY))', NULL, FALSE)
				 ->where('DATEDIFF(releases.date, NOW()) <= (SELECT value FROM users_meta WHERE users_meta.key=\'days_to_notify\' AND user_id=notify.user_id)', NULL, FALSE)
				 ->where('(SELECT value FROM users_meta WHERE users_meta.key=\'notify_start\' AND user_id=notify.user_id) <= HOUR(NOW())')
				 ->where('(SELECT value FROM users_meta WHERE users_meta.key=\'notify_end\' AND user_id=notify.user_id) >= HOUR(NOW())');
		
		$this->db->order_by('notify_order', 'asc', FALSE);
		$notifs = $this->db->get('notify');
		
		//echo $this->db->last_query();
		
		$to_notify = array();
		
		foreach($notifs->result() as $notif)
		{
			if($this->user_m->notify($notif->movie_id, $notif->user_id, $notif->type, $notif->notify_id))
			{
				$this->cli->write('Notification '.$notif->id.' sent', 'green');
			}
			else
			{
				$this->cli->write('Notification '.$notif->id.' failed', 'red');
			}
			
			if($notif->notify_order > 1)
			{
				$this->cli->wait(1, TRUE); // Sleep for a bit
			}
		}
		
		$ttl = mktime(date('G') + 1, 0, 0) - time();
		
		$this->cli->write('Sleeping until next hour: '.ceil($ttl/60).' minutes');
		
		$this->cli->wait($ttl);
		// And away we go again...
		$this->all();
	}
	
	private function _days($start = NULL, $end)
	{
		if($start == NULL)
		{
			$start = time();
		}
		
		$diff = $end - $start;
		
		return round($diff / 86400);
	}
}
