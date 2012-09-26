<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Notify extends Cron
{
	public function run($timezone = 'UTC')
	{
		$this->cli->clear_screen();
		$this->cli->write('Starting cron for timezone: '.$timezone);
		
		$this->load->helper('date');
		$time = gmt_to_local(now(), $timezone, FALSE);

		$hour = date('G', $time);
		
		$this->cli->write('Grabbing notifications for this hour ('.$hour.')');
		
		$this->db->select('DISTINCT notify.*, notify.id AS notify_id', FALSE)
				 ->select('movies.*')
				 ->select('releases.*')
				 ->select('IFNULL((SELECT value FROM users_meta WHERE users_meta.key=\'notify_start\' AND user_id=notify.user_id), 0) AS notify_start', FALSE)
				 ->select('IFNULL((SELECT value FROM users_meta WHERE users_meta.key=\'notify_end\' AND user_id=notify.user_id), 24) AS notify_end', FALSE)
				 ->select('(IFNULL((SELECT value FROM users_meta WHERE users_meta.key=\'notify_end\' AND user_id=notify.user_id), 24) - '.$hour.') as notify_order', FALSE);
				 
		$this->db->join('movies', 'notify.movie_id = movies.id')
				 ->join('releases', 'movies.id = releases.movie_id', 'inner');
		
		$this->db->where('notify.id NOT IN (SELECT notify_id FROM notifications)', NULL, FALSE)
				 ->where('releases.date BETWEEN CURDATE() AND DATE(DATE_ADD(CURDATE(), INTERVAL IFNULL((SELECT value FROM users_meta WHERE users_meta.key=\'days_to_notify\' AND user_id=notify.user_id), 7) DAY))', NULL, FALSE)
				 ->where('releases.country_id = IFNULL((SELECT country FROM users WHERE id=notify.user_id), 226)')
				 ->where('DATEDIFF(releases.date, NOW()) <= IFNULL((SELECT value FROM users_meta WHERE users_meta.key=\'days_to_notify\' AND user_id=notify.user_id), 7)', NULL, FALSE)
				 ->where('IFNULL((SELECT value FROM users_meta WHERE users_meta.key=\'notify_start\' AND user_id=notify.user_id), 0) <= '.$hour)
				 ->where('IFNULL((SELECT value FROM users_meta WHERE users_meta.key=\'notify_end\' AND user_id=notify.user_id), 24) >= '.$hour)
				 ->where('IFNULL((SELECT value FROM users_meta WHERE users_meta.key=\'timezone\' AND user_id=notify.user_id), \'UTC\') = \''.$timezone.'\'');
		
		$this->db->order_by('notify_order', 'asc', FALSE);
		$notifs = $this->db->get('notify');
		
		$to_notify = array();
		
		foreach ($notifs->result() as $notif)
		{
			if ($this->user_m->notify($notif->movie_id, $notif->user_id, $notif->type, $notif->notify_id))
			{
				$this->cli->write('Notification '.$notif->notify_id.' sent', 'green');
			}
			else
			{
				$this->cli->error('Notification '.$notif->notify_id.' failed', 'red');
			}
			
			if ($notif->notify_order > 1)
			{
				$this->cli->wait(1, TRUE); // Sleep for a bit
			}
		}

		$reverse_offset = timezones($timezone) - (timezones($timezone) * 2);

		$next_hour = (floor(($time + 3600) / 3600) * 3600) + ($reverse_offset * 3600);


		$this->cli->write('Sleeping until next hour: '.ceil(($next_hour - time())/60).' minutes');
		
		$this->cli->wait_until($next_hour);
		// And away we go again...
		$this->run($timezone);
	}
	
	private function _days($start = NULL, $end)
	{
		if ($start == NULL)
		{
			$start = time();
		}
		
		$diff = $end - $start;
		
		return round($diff / 86400);
	}
}
