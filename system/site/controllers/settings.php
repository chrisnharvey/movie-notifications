<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Settings extends Restricted {
	
	public function __construct()
	{
		parent::__construct();
		
		check_login(); // Redirect to the login page if the user is not logged in
		
		// Load required files
		$this->load->helper('form');
		$this->load->library('form_validation');
	}
	
	public function index()
	{
		$this->page->title = 'Account Settings';
		
		$data = array();
		
		// Load required files
		$this->load->helper('date');
		
		$this->form_validation->set_rules('email', 'Email', 'required|valid_email');
		$this->form_validation->set_rules('timezones', 'Timezone', 'required|callback__valid_timezone');
		$this->form_validation->set_rules('password', 'Password', 'required|callback__valid_password');
		
		if ($this->form_validation->run() === TRUE)
		{
			$update = $this->user_m->update_settings($this->input->post('email'), $this->input->post('timezones'));
			
			if($update)
			{
				$data['success'] = TRUE;
			}
			else
			{
				$data['success'] = FALSE;
			}
		}
		
		$this->page->show('settings/account', $data);
	}
	
	public function _valid_password($value)
	{
		if($this->user_m->valid_password($value))
		{
			return TRUE;
		}
		else
		{
			$this->form_validation->set_message('_valid_password', 'The password you entered was incorrect.');
			return FALSE;
		}
	}
	
	public function _valid_timezone($value)
	{
		$timezone_array = array(
								'UM12',
								'UM11',
								'UM10',
								'UM95',
								'UM9',
								'UM8',
								'UM7',
								'UM6',
								'UM5',
								'UM45',
								'UM4',
								'UM35',
								'UM3',
								'UM2',
								'UM1',
								'UTC',
								'UP1',
								'UP2',
								'UP3',
								'UP35',
								'UP4',
								'UP45',
								'UP5',
								'UP55',
								'UP575',
								'UP6',
								'UP65',
								'UP7',
								'UP8',
								'UP875',
								'UP9',
								'UP95',
								'UP10',
								'UP105',
								'UP11',
								'UP115',
								'UP12',
								'UP1275',
								'UP13',
								'UP14');
								
		if(in_array($value, $timezone_array))
		{
			return TRUE;
		}
		else
		{
			$this->form_validation->set_message('_valid_timezone', 'The timezone you specified was not valid');
		}
	
	}
	
	public function password()
	{
		$this->page->title = 'Password Settings';
		
		$data = array();
		
		$this->form_validation->set_rules('password', 'current password', 'required|min_length[6]|callback__password_check');
		$this->form_validation->set_rules('new_password', 'new password', 'required|min_length[6]');
		$this->form_validation->set_rules('verify_password', 'verify password', 'required|min_length[6]|matches[new_password]');

		if ($this->form_validation->run() === TRUE)
		{
			$change = $this->user_m->change_password($this->input->post('new_password'));
			
			if ($change)
			{
				$data['success'] = TRUE;
			}
			else
			{
				$data['success'] = FALSE;
			}
		}
		
		$this->page->show('settings/password', $data);
	}
	
	public function _password_check($value) // This method is not available in the uri
	{
		// Do your thang!
		$this->load->library("Hash");
		if (Hash::CheckPassword($value, $this->session->userdata('password')) == TRUE)
		{
			return TRUE;
		}
		else
		{
			$this->form_validation->set_message('_password_check', 'The password you entered was incorrect');
			return FALSE;
		}
	}

	public function mobile($country = NULL)
	{
		$country = 'uk'; // Force the country to UK
		
		$this->page->title = 'Mobile Settings';
		
		if($this->session->userdata('verified') === '0')
		{
			// Just show a page requesting the user to verify their email address
			$this->page->show('settings/mobile');
		}
		elseif($country === NULL)
		{
			if($this->user_m->meta('mobile_country') == 'US')
			{
				redirect(site_url('settings/mobile/us'));
			}
			else
			{
				$country = $this->input->server('HTTP_CF_IPCOUNTRY');
			
				if(!$country || $country == 'XX' || $country == 'GB')
				{
					redirect(site_url('settings/mobile/uk'));
				}
				else
				{
					redirect(site_url('settings/mobile/us'));
				}
			}
		}
		elseif($country == 'uk')
		{
			if($this->input->get('cancel') AND $this->input->get('mobile') == $this->user_m->meta('mobile_number'))
			{
				$this->user_m->add_meta('mobile_number', NULL);
				$this->user_m->add_meta('mobile_verified', NULL);
				$this->user_m->add_meta('mobile_verification_code', NULL);
				$this->user_m->add_meta('mobile_country', NULL);
			}
			
			$this->load->library('form_validation');
			$this->load->helper('form');
			
			if($this->user_m->meta('mobile_number') && $this->user_m->meta('mobile_country') == 'UK')
			{
				if($this->user_m->meta('mobile_verified'))
				{
					$this->page->show('settings/mobile/uk/verified');
				}
				else
				{
					// Ask for text verification code
					$this->form_validation->set_rules('code', 'verification code', 'required|alpha_numeric|min_length[5]|max_length[8]|callback__valid_code');
			
					if($this->form_validation->run())
					{
						// Do your thing and verify the mobile
						
						$verify = $this->user_m->verify_mobile($this->input->post('code'));
						
						if($verify)
						{
							$this->page->show('settings/mobile/uk/verified');
						}
						else
						{
							$data['error'] = "The was an error verifying your mobile";
							$this->page->show('settings/mobile/uk/step2', $data);
						}
					}
					else
					{
						$this->page->show('settings/mobile/uk/step2');
					}
				}
			}
			else
			{
				$this->form_validation->set_rules('mobile', 'mobile number', 'required|numeric|is_natural|min_length[11]|max_length[11]|callback__can_send');
			
				if($this->form_validation->run())
				{
					// Do your thing and send the message
					
					$add = $this->user_m->add_uk_mobile($this->input->post('mobile'));
					
					if($add)
					{
						$this->page->show('settings/mobile/uk/step2');
					}
					else
					{
						$this->page->show('settings/mobile/uk/step1');
					}
					
				}
				else
				{
					$this->page->show('settings/mobile/uk/step1');
				}
			}
		}
		elseif($country == 'us')
		{
			if($this->input->get('cancel') AND $this->input->get('mobile') == $this->user_m->meta('mobile_number'))
			{
				$this->user_m->add_meta('mobile_number', NULL);
				$this->user_m->add_meta('mobile_verified', NULL);
				$this->user_m->add_meta('mobile_verification_code', NULL);
				$this->user_m->add_meta('mobile_country', NULL);
			}
			
			$this->load->library('form_validation');
			$this->load->helper('form');
			
			if($this->user_m->meta('mobile_number') && $this->user_m->meta('mobile_country') == 'US')
			{
				if($this->user_m->meta('mobile_verified'))
				{
					$this->page->show('settings/mobile/us/verified');
				}
				else
				{
					$this->page->show('settings/mobile/us/step2');
				}
			}
			else
			{
				$this->form_validation->set_rules('mobile', 'mobile number', 'required|numeric|min_length[5]|max_length[15]');
			
				if($this->form_validation->run())
				{
					// Do your thing and send the message
					
					$add = $this->user_m->add_us_mobile($this->input->post('mobile'));
					
					if($add)
					{
						$this->page->show('settings/mobile/us/step2');
					}
					else
					{
						$this->page->show('settings/mobile/us/step1');
					}
					
				}
				else
				{
					$this->page->show('settings/mobile/us/step1');
				}
			}
		}
	}

	public function _can_send($number)
	{
		if(substr($number, 0, 2) == '07')
		{
			if(!$this->user_m->can_send_sms($number))
			{
				$this->form_validation->set_message('_can_send', 'You have exceeded the maximum number of SMS messages for this hour');
				return FALSE;
			}
			
			return TRUE;
		}
		else
		{
			$this->form_validation->set_message('_can_send', 'Your mobile number must start with 07');
			return FALSE;
		}
	}

	public function _valid_code($value)
	{
		if($value != $this->user_m->meta('mobile_verification_code'))
		{
			$this->form_validation->set_message('_valid_code', 'The code you entered was invalid');
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
	
	public function notifications()
	{
		$this->page->title = 'Notification Settings';
		
		$this->page->append_meta(js('disable_times.js'));
		
		$data = array();
		
		if($this->session->userdata('verified') === '1')
		{
			// All page functions shall go in here
			$this->form_validation->set_rules('notify_via', 'notify via', 'required|callback__valid_notifier');
			$this->form_validation->set_rules('days_to_notify', 'days notice', 'required|less_than[8]|greater_than[0]');
			$this->form_validation->set_rules('notify_start', 'notification start time', 'required|less_than[24]');
			$this->form_validation->set_rules('notify_end', 'notification end time', 'required|less_than[25]|greater_than[0]|callback__valid_end');
			
			if($this->session->userdata('verified') == '1')
			{
				$data['notify_via']['email'] = 'Email';
			}
			
			if($this->user_m->meta('iphone_device_token'))
			{
				$data['notify_via']['iphone'] = 'iPhone';
			}
			
			if($this->user_m->meta('mobile_verified'))
			{
				$data['notify_via']['sms'] = 'Text Message';
			}
			
			$data['days_to_notify'] = array('1' => '1 day',
								  '2' => '2 days',
								  '3' => '3 days',
								  '4' => '4 days',
								  '5' => '5 days',
								  '6' => '6 days',
								  '7' => '7 days');
			
			$data['notify_start'] = array('0' => 'midnight',
										  '1' => '1 AM',
										  '2' => '2 AM',
										  '3' => '3 AM',
										  '4' => '4 AM',
										  '5' => '5 AM',
										  '6' => '6 AM',
										  '7' => '7 AM',
										  '8' => '8 AM',
										  '9' => '9 AM',
										  '10' => '10 AM',
										  '11' => '11 AM',
										  '12' => 'noon',
										  '13' => '1 PM',
										  '14' => '2 PM',
										  '15' => '3 PM',
										  '16' => '4 PM',
										  '17' => '5 PM',
										  '18' => '6 PM',
										  '19' => '7 PM',
										  '20' => '8 PM',
										  '21' => '9 PM',
										  '22' => '10 PM',
										  '23' => '11 PM');
										  
			$data['notify_end'] = $data['notify_start']; // Copy over the start array as it is the same as the end array
			
			unset($data['notify_end']['0']); // We don't user 'midnight' as the first element for the end array, unset it
			
			$data['notify_end']['24'] = 'midnight'; // Add 'midnight' to the end
			
			if($this->form_validation->run())
			{
				$this->db->trans_start();
				$this->user_m->add_meta('notify_via', $this->input->post('notify_via'));
				$this->user_m->add_meta('days_to_notify', $this->input->post('days_to_notify'));
				$this->user_m->add_meta('notify_start', $this->input->post('notify_start'));
				$this->user_m->add_meta('notify_end', $this->input->post('notify_end'));
				$this->db->trans_complete();
			}
			else
			{
				
			}
			
		}

		$this->page->show('settings/notifications', $data);
	}
	
	public function _valid_notifier($value)
	{
		if($this->session->userdata('verified') !== '1')
		{
			$this->form_validation->set_message('_valid_notifier', 'You must verify your email address before you can receive notifications');
			return FALSE;
		}
		
		if($value == 'iphone' AND !$this->user_m->meta('iphone_device_token'))
		{
			$this->form_validation->set_message('_valid_notifier', 'You must setup your iPhone before you can receive notifications via iPhone');
			return FALSE;
		}
		
		if($value == 'sms' AND !$this->user_m->meta('mobile_verified'))
		{
			$this->form_validation->set_message('_valid_notifier', 'You must setup your mobile before you can receive notifications via SMS');
			return FALSE;
		}
		
		return TRUE;
	}
	
	public function _valid_end($value)
	{
		if($value <= $this->input->post('notify_start'))
		{
			$this->form_validation->set_message('_valid_end', 'Your notification end time must be later than the start time');
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
}

/* End of file */