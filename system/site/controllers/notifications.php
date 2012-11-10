<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Notifications extends Restricted {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('movie_m');
	}
	
	public function index()
	{
		$this->db->limit(10);
		$data['notifs'] = $this->user_m->get_notifications(FALSE, TRUE);
		
		$this->db->limit(10);
		$data['scheduled'] = $this->user_m->get_scheduled_notifications();
		
		$this->page->title = 'Notifications';
		
		$this->page->show('notifications', $data);
	}
	
	public function app()
	{
		$notifs = $this->user_m->get_notifications();

		foreach ($notifs as &$notif)
		{
			$notif['poster_url'] = $this->movie_m->poster($notif['movie_id']);
		}

		$this->page->json($notifs);
	}
	
	public function ajax()
	{
		if($this->input->is_ajax_request())
		{
			$notifs = $this->user_m->get_notifications(TRUE, TRUE);
			
			$this->page->json($notifs);
		}
		else
		{
			show_404();
		}
	}
}

/* End of file */