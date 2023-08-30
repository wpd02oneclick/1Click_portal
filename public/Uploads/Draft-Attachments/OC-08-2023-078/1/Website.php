<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	class Website extends CI_Controller {
		private $logindetail = "not";
		private $data = "";
		function __construct() {
			parent::__construct();
			$this->load->library('login_lib');
			$logindetail = $this->login_lib->check();
			if($logindetail['codestatus']=="ok"){
				// User Library
				$this->load->library('user_lib');
				$data['userdata'] = $this->user_lib->userinfo();
				$data['permission'] = $this->user_lib->permission();
				$data['companywebsites'] = $this->user_lib->companywebsite($data['userdata']['level']);
				$data['notifications'] = $this->user_lib->notifications();
				$data['unreadnotification'] = $this->user_lib->unreadnotification();
				$data['unseensubmittion'] = $this->user_lib->unseensubmittion();
				$data['emailpermission'] = $this->user_lib->emailpermission();
				$data['markattendance'] = $this->user_lib->markattendance($data['userdata']['employeeid'],$data['userdata']['companyname']);
				//echo '<pre>';
				//print_r($data);
				//echo '</pre>';
			}
			else{
				redirect(base_url());
			}
			$this->logindetail = $logindetail;
			$this->data = $data;
		}
		public function index(){
			$logindetail = $this->logindetail;
			$data = $this->data;
			if($logindetail['codestatus']=="ok"){
				if($data['userdata']['level']=="Administrator"){
					$this->load->library('website_lib');
					$data['websites'] = $this->website_lib->lists();
					$this->load->view('web3/website',$data);
				}
				else{
					$this->load->view('web3/notpermission',$data);
				}
			}
			else{
				redirect(base_url());
			}
		}
		public function deletewebsite(){
			$logindetail = $this->logindetail;
			$data = $this->data;
			if($logindetail['codestatus']=="ok"){
				if($data['userdata']['level']=="Administrator"){
					$id = getsegment(3);
					$this->load->library('website_lib');
					$this->website_lib->deleted($id);
					redirect(base_url('website'));
				}
				else{
					$this->load->view('web3/notpermission',$data);
				}
			}
			else{
				redirect(base_url());
			}
		}
	}
?>