<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_m extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->driver('cache', array('adapter' => 'apc', 'backup' => 'file'));
	}
	
	public function register($username, $email, $password, $country = 226)
	{
		$this->load->library('phpass');
		$password = $this->phpass->HashPassword($password);
		
		$verify_hash = md5(uniqid().$email.microtime());
		
		$insert = $this->db->insert('users', array(
			'username'		=> $username,
			'password'		=> $password,
			'email'			=> $email,
			'verify_hash'	=> $verify_hash,
			'join_ip'		=> $this->input->ip_address()
		));
		
		if(!$insert)
			return FALSE;

		return $this->send_verification_email($this->db->insert_id());
	}
	
	public function send_verification_email($user_id = NULL)
	{
		if($user_id === NULL)
			$user_id = $this->session->userdata('user_id');
		
		if(!$last_sent = $this->meta('verification_sent'))
		{
			$send = TRUE;
		}
		else
		{
			if(round(microtime(TRUE)) - round($last_sent) >= 900) // Only send the email if its been more than 15 minutes since the last one was sent
			{
				$send = TRUE;
			}
			else
			{
				$send = FALSE;
			}	
		}
		
		$hash = md5(uniqid().$this->session->userdata('email').microtime());
		
		$data['name'] = $this->session->userdata('username');
		$data['url'] = site_url('register/verify/'.$hash);
		
		if($send)
		{
			$update_db = $this->db->update('users', array('verify_hash' => $hash), array('id' => $user_id, 'verified' => '0'));
		}
		else
		{
			$update_db = FALSE;
		}
		
		if($update_db)
		{
			$this->load->library('email');
			$this->load->config('email');
			
			$this->email->from($this->config->item('from_email'), $this->config->item('from_name'));
			$this->email->to($this->session->userdata('email'));
			
			$this->email->subject('Please verify your email address');
			$this->email->message($this->load->view('emails/verify_your_email', $data, TRUE));
			
			$this->email->send();

			$this->add_meta('verification_sent', microtime(TRUE));
			
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}

	public function check_verification_hash($hash)
	{
		$result = $this->db->update('users', array('verified' => '1'), array('id' => $this->session->userdata('user_id'), 'verify_hash' => $hash, 'verified' => '0'));
		
		if($this->db->affected_rows())
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
	
	function login($identity, $password)
	{
		// Brute force protection
		$this->db->insert('login_attempts', array('ip_address' => $this->input->ip_address()));

		$this->db->where('username', $identity)
				 ->or_where('email', $identity);
		$query = $this->db->get('users', 1);

		if($query->num_rows())
		{
			$this->load->library('phpass');
			if ($this->phpass->CheckPassword($password, $query->row()->password) == TRUE)
			{
				if($query->row()->deactivated == 1)
				{
					return "This account has recently been deactivated, if this is a mistake then please email us IMMEDIATELY";
				}
				elseif($query->row()->suspended == 1)
				{
					return "This account has been suspended, email us for more details";
				}
				else
				{
					$this->db->delete('login_attempts', array('ip_address' => $this->input->ip_address()));
					$data = array("user_id" => $query->row()->id, "username" => $query->row()->username, "email" => $query->row()->email, "country" => $query->row()->country, "verified" => $query->row()->verified, "logged_in" => TRUE);
					$this->session->set_userdata($data);
					return TRUE;
				}
			}
		}
		
		return FALSE; // If we got this far then clearly the supplied details are invalid, return false.
	}

	public function exceeded_login_attempts()
	{
		if($this->db->where('ip_address', $this->input->ip_address())
					->get('login_attempts')
					->num_rows() >= 5)
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}

	public function forgot_password($identity)
	{
		$this->db->select('id')
				 ->where('username', $identity)
				 ->or_where('email', $identity);

		$query = $this->db->get('users');

		if ($query->num_rows())
		{
			$key = md5(uniqid().$query->row()->id.rand().microtime());

			$this->add_meta('forgot_key', $key, $query->row()->id);
			$this->add_meta('forgot_expires', strtotime('+24 hours'), $query->row()->id);

			// Send an email out.
			if ($this->send_email('Forgot your password?', 'forgot_password', array('key' => $key), $query->row()->id))
			{
				return TRUE;
			}
			else
			{
				return 'There was an error whilst sending your password reset email';
			}
		}
		else
		{
			return "The username or email you entered is not registered with Movie Notifications";
		}
	}

	public function check_forgot_password($identity, $key)
	{
		$this->db->select('id')
				 ->where('username', $identity)
				 ->or_where('email', $identity);

		$query = $this->db->get_where('users');

		if ($query->num_rows())
		{
			if ($this->meta('forgot_key', $query->row()->id) === $key AND $this->meta('forgot_expires') > time())
			{
				return TRUE;
			}
			else
			{
				return 'The key specified is not valid or has expired';
			}
		}
		else
		{
			return 'The username or email specified is not registered with Movie Notifications';
		}
	}

	public function reset_password($identity, $key, $new_password)
	{
		if ($this->check_forgot_password($identity, $key))
		{
			$user_id = $this->id_from_identity($identity);
			
			$this->db->trans_start();
			$this->add_meta('forgot_key', NULL, $user_id);
			$this->add_meta('forgot_expires', NULL, $user_id);
			$this->change_password($new_password, $user_id);
			$this->db->trans_complete();

			return $this->db->trans_status();
		}
	}

	public function id_from_identity($identity)
	{
		$this->db->where('username', $identity)
				 ->or_where('email', $identity);

		$query = $this->db->get('users');

		if ($query->num_rows())
		{
			return $query->row()->id;
		}
		else
		{
			return FALSE;
		}
	}
	
	public function valid_country($country_id)
	{
		$query = $this->db->get_where('countries', array('enabled' => 1, 'country_id' => $country_id));
		
		if($query->num_rows())
		{
			return TRUE;
		}
		
		return FALSE;
	}
	
	public function valid_password($password, $user_id = NULL)
	{
		if($user_id === NULL)
		{
			$user_id = $this->session->userdata('user_id');
		}
		
		$query = $this->db->get_where('users', array('id' => $user_id));
		
		if($query->num_rows())
		{
			$this->load->library('phpass');
			if ($this->phpass->CheckPassword($password, $query->row()->password) == TRUE)
			{
				return TRUE;
			}
		}
		
		return FALSE;
	}
	
	public function exists($user)
	{
		$user = xss_clean($user);
		
		if(!$this->cache->get("mn_user_exists_".$user))
		{
			if(is_numeric($user))
			{
				if($this->db->get_where('users', array('id' => $user))->num_rows())
				{
					$this->cache->save("mn_user_exists_".$user, TRUE, 86400);
					return TRUE;
				}
				else
				{
					return FALSE;
				}
			}
			else
			{
				if($this->db->get_where('users', array('username' => $user))->num_rows())
				{
					$this->cache->save("mn_user_exists_".$user, TRUE, 86400);
					return TRUE;
				}
				else
				{
					return FALSE;
				}
			}
		}
		else
		{
			return TRUE;
		}
	}
	
	public function meta($meta_key, $user_id = NULL)
	{
		if($user_id === NULL)
		{
			$user_id = $this->session->userdata('user_id');
		}
		
		$meta_key = xss_clean($meta_key);
		
		if(!$data = $this->cache->get("mn_user_meta_".$user_id."_".$meta_key)) // Check the data is not already cached
		{
			$query = $this->db->get_where('users_meta', array('user_id' => $user_id, 'key' => $meta_key));
			
			if(!$query->num_rows() OR $query->row()->value == NULL)
			{
				return FALSE;
			}
			else
			{
				$data = $query->row()->value;
			}
			
			$this->cache->save("mn_user_meta_".$user_id."_".$meta_key, $data, 86400); // We're done, save this to the cache for a day
		}
		
		return $data;
	}
	
	public function add_meta($meta_key, $meta_value, $user_id = NULL)
	{
		if($user_id === NULL)
		{
			$user_id = $this->session->userdata("user_id");
		}
		
		if($this->exists($user_id))
		{
			$insert_array = array("user_id" => $user_id, "key" => $meta_key, "value" => $meta_value);
			$update_array = array("value" => $meta_value);
	
			$query = $this->db->where("key", $meta_key);
			$query = $this->db->get_where('users_meta', array('user_id' => $user_id));
					
			if(!$query->num_rows())
			{
				$this->db->insert("users_meta", $insert_array);
			}
			else
			{
				$this->db->where("user_id", $user_id);
				$this->db->where("key", $meta_key);
				$this->db->update("users_meta", $update_array);
			}
			
			$this->cache->delete("mn_user_meta_".$user_id."_".$meta_key); // Delete the cached file (if any)
			
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
	
	public function add_us_mobile($number, $user_id = NULL)
	{
		if($user_id === NULL)
		{
			$user_id = $this->session->userdata('user_id');
			$username = $this->session->userdata('username');
		}
		else
		{
			$username = $this->db->get_where('user', array('id' => $user_id))->row()->username;
		}
		
		
		$verification_code = generate_password(rand(5, 8)); // Generate verification code, min 5, max 8
		
		$this->db->trans_start();
		$this->user_m->add_meta('mobile_number', $number);
		$this->user_m->add_meta('mobile_verification_code', $verification_code);
		$this->user_m->add_meta('mobile_country', 'US');
		$this->db->trans_complete();
		
		if ($this->db->trans_status() !== FALSE)
		{
			// The verification code was added and the number was added, text the user with this code.
			
			if($this->can_send_sms($number, $user_id))
			{
				$this->load->library('clickatell');
				
				$data['code'] = $verification_code;
				$data['name'] = $username;
				
				$message = $this->load->view('texts/verify_mobile', $data, TRUE);
				
				$send = $this->clickatell->send_message($number, $message);
				$send = TRUE;
				
				if($send)
				{
					$this->_log_sms($number, $message, $user_id);
					return TRUE;
				}
				else
				{
					$this->user_m->add_meta('mobile_number', NULL);
					$this->user_m->add_meta('mobile_verification_code', NULL);
					$this->user_m->add_meta('mobile_country', NULL);
					return FALSE;
				}
			}
			else
			{
				$this->user_m->add_meta('mobile_number', NULL);
				$this->user_m->add_meta('mobile_verification_code', NULL);
				$this->user_m->add_meta('mobile_country', NULL);
				return FALSE;
			}
		}
	}
	
	public function add_uk_mobile($number, $user_id = NULL)
	{
		if($user_id === NULL)
		{
			$user_id = $this->session->userdata('user_id');
			$username = $this->session->userdata('username');
		}
		else
		{
			$username = $this->db->get_where('user', array('id' => $user_id))->row()->username;
		}
		
		
		$verification_code = generate_password(rand(5, 8)); // Generate verification code, min 5, max 8
		
		$this->db->trans_start();
		$this->user_m->add_meta('mobile_number', $number);
		$this->user_m->add_meta('mobile_verification_code', $verification_code);
		$this->user_m->add_meta('mobile_country', 'UK');
		$this->db->trans_complete();
		
		if ($this->db->trans_status() !== FALSE)
		{
			// The verification code was added and the number was added, text the user with this code.
			
			if($this->can_send_sms($number, $user_id))
			{
				$this->load->library('textmarketer');
				
				$data['code'] = $verification_code;
				$data['name'] = $username;
				
				$message = $this->load->view('texts/verify_mobile', $data, TRUE);
				
				$send = $this->textmarketer->send($number, $message);
				$send = TRUE;
				
				
				
				if($send)
				{
					$this->_log_sms($number, $message, $user_id);
					return TRUE;
				}
				else
				{
					$this->user_m->add_meta('mobile_number', NULL);
					$this->user_m->add_meta('mobile_verification_code', NULL);
					$this->user_m->add_meta('mobile_country', NULL);
					return FALSE;
				}
			}
			else
			{
				$this->user_m->add_meta('mobile_number', NULL);
				$this->user_m->add_meta('mobile_verification_code', NULL);
				$this->user_m->add_meta('mobile_country', NULL);
				return FALSE;
			}
		}
	}

	private function _log_sms($number, $message, $user_id = NULL)
	{
		if($user_id === NULL)
		{
			$user_id = $this->session->userdata('user_id');
		}
		$insert = $this->db->insert('sms_sent', array('user_id' => $user_id, 'number' => $number, 'message' => $message));

		if($insert)
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
	
	public function can_send_sms($number, $user_id = NULL)
	{
		if($user_id === NULL)
		{
			$user_id = $this->session->userdata('user_id');
		}
		
		$this->db->where('sent > DATE_SUB(NOW(), INTERVAL 1 HOUR)');
		$this->db->where('(user_id = '.(int)$user_id.' OR number = '.$this->db->escape($number).')');
		$sms = $this->db->get('sms_sent');
		
		//exit($this->db->last_query());
		
		if($sms->num_rows() < 10)
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
		
		
	}
	
	public function verify_mobile($code, $user_id = NULL)
	{
		if($user_id === NULL)
		{
			$user_id = $this->session->userdata('user_id');
		}
		
		if($this->meta('mobile_verification_code') == $code)
		{
			$add = $this->add_meta('mobile_verified', 1);
			
			if($add)
			{
				return TRUE;
			}
			else
			{
				return FALSE;
			}
		}
		else
		{
			return FALSE;
		}
	}

	public function change_password($new_password, $user_id = NULL)
	{
		if ($user_id === NULL)
		{
			$user_id = $this->session->userdata('user_id');
		}
		
		$this->load->library('phpass');
		$hashed = $this->phpass->HashPassword($new_password);
		
		$update = $this->db->update('users', array('password' => $hashed), array('id' => $user_id));
		
		if ($update)
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
	
	public function update_settings($email, $country, $timezone, $user_id = NULL)
	{
		if($user_id === NULL)
		{
			$user_id = $this->session->userdata('user_id');
		}
		
		$this->db->trans_start();
		
		$this->db->update('users', array('email' => $email, 'country' => $country, 'verified' => ($this->session->userdata('email') == $email ? $this->session->userdata('verified') : 0)), array('id' => $user_id));
		$this->add_meta('timezone', $timezone);
		
		$this->db->trans_complete();
		
		if ($this->db->trans_status() === FALSE)
		{
		    return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
	
	public function get_notifications($unread_only = FALSE, $mark_as_read = FALSE, $user_id = NULL)
	{
		if($user_id === NULL)
		{
			$user_id = $this->session->userdata('user_id');
		}
		
		if($unread_only)
		{
			$this->db->where('read', 0);
		}
		
		$this->db->select('notifications.id, notifications.notification, notifications.timestamp, notifications.read, notify.type, movies.id as movie_id, movies.title');
		
		$this->db->join('notify', 'notifications.notify_id = notify.id', 'left');
		$this->db->join('movies', 'notify.movie_id = movies.id', 'left');
		
		$this->db->order_by('timestamp', 'desc');
		$notifs = $this->db->get_where('notifications', array('notifications.user_id' => $user_id), 10);
		
		$return = array();
		
		foreach($notifs->result() as $notif)
		{
			array_push($return, array('id' => $notif->id, 
									  'notification' => $notif->notification, 
									  'type' => $notif->type, 
									  'movie_id' => $notif->movie_id, 
									  'title' => $notif->title, 
									  'read' => ($notif->read == 1) ? TRUE : FALSE, 
									  'timestamp' => strtotime($notif->timestamp)
									  )
					  );
			
			if($mark_as_read)
			{
				$this->db->update('notifications', array('read' => 1), array('id' => $notif->id));
			}
		}
		
		return $return;
	}

	public function get_scheduled_notifications($user_id = NULL)
	{
		if($user_id === NULL)
			$user_id = $this->session->userdata('user_id');
		
		$country_id = $this->session->userdata('country');
		
		
		$this->db->select('notify.*, movies.id as movie_id, movies.title, releases.date');
		$this->db->join('movies', 'notify.movie_id = movies.id', 'left');
		$this->db->join('releases', 'releases.movie_id = movies.id', 'right');
		
		$this->db->where('releases.country_id', $country_id);
		$this->db->where('releases.type = notify.type');
		$this->db->where('releases.date > CURDATE()');
		$this->db->where('notify.id NOT IN (SELECT notify_id FROM notifications WHERE notify_id=notify.id)', NULL, FALSE);
		
		$this->db->order_by('date', 'asc');
		$notifs = $this->db->get_where('notify', array('notify.user_id' => $user_id), 10);

		$return = array();

		foreach($notifs->result() as $notif)
		{
			array_push($return, array(
				'id' => $notif->id, 
				'type' => $notif->type, 
				'movie_id' => $notif->movie_id, 
				'title' => $notif->title, 
				'synopsis' => $this->movie_m->meta($notif->movie_id, 'synopsis'),
				'timestamp' => strtotime($notif->timestamp)
			));
		}
		
		return $return;
	}
	
	// Has the user setup a notification for the specified movie and type
	function notif_for($movie_id, $type = 'theaters')
	{
		if($type !== 'theaters')
		{
			$type = 'dvd';
		}
		
		$this->load->model('movie_m');
		
		if ($this->movie_m->exists($movie_id))
		{
			$notify = $this->db->get_where('notify', array(
				'movie_id' => $movie_id, 
				'user_id'  => $this->session->userdata('user_id'),
				'type'	  => $type
			));
			
			if($notify->num_rows())
			{
				return TRUE;
			}
			else
			{
				return FALSE;
			}
			
		}
		else
		{
			return FALSE;
		}
	}

	public function can_notify($movie_id, $type = 'theaters')
	{
		if($type == 'theaters')
		{
			$type = 'Theaters';
		}
		else
		{
			$type = 'DVD';
		}

		$country = $this->session->userdata('country');
		$user_id = $this->session->userdata('user_id');

		// Check that the user has not already received a notification for this movie
		$this->db->join('notify', 'notifications.notify_id = notify.id')
				 ->where('notify.type', $type)
				 ->where('notify.movie_id', $movie_id)
				 ->where('notifications.user_id', $user_id);

		$already_notified = $this->db->count_all_results('notifications');
		
		if ( ! $already_notified)
		{
			$movie = $this->db->get_where('releases', array('movie_id' => $movie_id, 'type' => $type, 'country_id' => $country));

			if(!$movie->num_rows())
			{
				if($type == 'DVD')
				{
					$theaters = $this->db->get_where('releases', array('movie_id' => $movie_id, 'type' => 'Theaters'));
					if($theaters->num_rows())
					{
						if(strtotime($theaters->row()->date) < strtotime('-2 years'))
						{
							return FALSE;
						}
					}
				}
				else
				{
					$dvd = $this->db->get_where('releases', array('movie_id' => $movie_id, 'type' => 'DVD'));
					if($dvd->num_rows())
					{
						return FALSE;
					}
				}
				
				return TRUE;
			}

			$date = strtotime($movie->row()->date);
			$today = time();
			
			if($date < $today)
			{
				return FALSE;
			}
		}
		else
		{
			return FALSE;
		}
		
		return TRUE;
	}
	
	// Add notification for the user
	function add_notify($id, $type = 'theaters')
	{
		if ($this->can_notify($id, $type))
		{
			$id = (int)$id;
			
			if($type != 'theaters')
				$type = 'dvd';
			
			if($type == 'dvd' AND $this->session->userdata('country') == 225)
				return FALSE;
			
			$insert = $this->db->insert("notify", array('user_id' => $this->session->userdata('user_id'), 'movie_id' => $id, 'type' => $type));
			
			if($insert)
			{
				return TRUE;
			}
			else
			{
				return FALSE;
			}
		}
		else
		{
			return FALSE;
		}
	}
	
	// Remove notification for the user
	function remove_notify($id, $type = 'theaters')
	{
		if ($this->can_notify($id, $type))
		{
			$id = (int)$id;
			
			if($type != 'theaters')
			{
				$type = 'dvd';
			}
			
			$delete = $this->db->delete("notify", array('user_id' => $this->session->userdata('user_id'), 'movie_id' => $id, 'type' => $type));
			
			if($delete)
			{
				return TRUE;
			}
			else
			{
				return FALSE;
			}
		}
		else
		{
			return FALSE;
		}
	}

	public function data($user_id = NULL)
	{
		if($user_id === NULL)
		{
			$user_id = $this->session->userdata('user_id');
		}
		
		$query = $this->db->select('username, email, country')
				 ->get_where('users', array('id' => $user_id));
				 
		return $query->row_array();
	}

	public function notify($movie_id, $user_id, $type, $notify_id)
	{
		$this->load->model('movie_m');

		if(!$this->movie_m->exists($movie_id) OR !$this->exists($user_id))
		{
			return FALSE;
		}
		
		$notify_via = $this->user_m->meta('notify_via', $user_id);
		$user = $this->data($user_id);
		
		$data['name'] = $user['username'];
		$data['title'] = $this->db->get_where('movies', array('id' => $movie_id))->row()->title;
		$data['date'] = $this->db->get_where('releases', array('movie_id' => $movie_id, 'type' => $type, 'country_id' => $user['country']))->row()->date;
		$days = $this->_days(NULL, strtotime($data['date']));
		
		if($days == 1)
		{
			$data['when'] = 'tomorrow';
		}
		else
		{
			$data['when'] = 'in '.$days.' days';
		}
		
		if($notify_via == 'sms')
		{
			$sent_via = 'sms';
			$message = $this->load->view('texts/notification_'.$type, $data, TRUE);
			$send =  $this->send_sms($message, $user_id);
		}
		elseif($notify_via == 'iphone')
		{
			$sent_via = 'iphone';
			$message = $this->load->view('texts/notification_'.$type, $data, TRUE);
			$send = $this->send_iphone($message, $user_id);
		}
		
		if(!isset($send) OR !$send OR $notify_via == 'email')
		{
			$sent_via = 'email';

			if ($type == 'Theaters')
			{
				$subject = $data['title'].' is released in theaters '.$data['when'];
			}
			else
			{
				$subject = $data['title'].' is released to DVD '.$data['when'];
			}
			

			$message = $this->load->view('texts/notification_'.$type, $data, TRUE);
			
			$send = $this->send_email($subject, 'notification_'.$type, $data, $user_id);
		}
		
		if($send)
		{
			$this->db->insert('notifications', array('user_id' => $user_id, 'notify_id' => $notify_id, 'notification' => $message, 'sent_via' => $sent_via));
			return TRUE;
		}
		
		return FALSE;
	}

	public function send_email($subject, $file, $data = NULL, $user_id = NULL)
	{
		if($user_id === NULL)
		{
			$user_id = $this->session->userdata('user_id');
		}
		
		if(!$this->exists($user_id))
		{
			return FALSE;
		}
		
		$user = $this->data($user_id);
		$email = $user['email'];

		$data['user'] = $user;
		
		$this->load->library('email');
		$this->load->config('email');
		
		$this->email->from($this->config->item('from_email'), $this->config->item('from_name'));
		$this->email->to($email);
		$this->email->subject(sprintf($subject));
		$this->email->message($this->load->view('emails/html/'.$file, $data, TRUE));
		$this->email->set_alt_message($this->load->view('emails/text/'.$file, $data, TRUE));
		
		if($this->email->send())
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
		
		return TRUE;
	}
	
	public function send_sms($message, $user_id = NULL)
	{
		if($user_id === NULL)
		{
			$user_id = $this->session->userdata('user_id');
		}
		
		
		if($this->meta('mobile_country', $user_id) == 'UK')
		{
			$number = $this->meta('mobile_number', $user_id);
			
			if($this->can_send_sms($number, $user_id))
			{
				$this->load->library('textmarketer');
				$send = $this->textmarketer->send($number, $message);
			//	$send = TRUE;
				
				if($send)
				{
					$this->_log_sms($number, $message, $user_id);
					return TRUE;
				}
				else
				{
					return FALSE;
				}
			}
		}
		elseif ($this->meta('mobile_country', $user_id) == 'US')
		{			
			$number = $this->meta('mobile_number', $user_id);
			
			if($this->can_send_sms($number, $user_id))
			{
				$this->load->library('clickatell');
				$send = $this->clickatell->send_message($number, $message);
			//	$send = TRUE;
				
				if ($send)
				{
					$this->_log_sms($number, $message, $user_id);
					return TRUE;
				}
				else
				{
					return FALSE;
				}
			}
		}
				
		return FALSE;
	}
	
	private function _days($start = NULL, $end)
	{
		if($start == NULL)
		{
			$start = time();
		}
		
		$diff = $end - $start;
		
		return round($diff / 86400) == 0 ? ceil($diff / 86400) : round($diff / 86400);
	}
}
