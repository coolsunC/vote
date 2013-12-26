<?php
class Vote extends CI_Controller{

 function __construct()
 {
  parent::__construct();
  $this->load->library('form_validation');
  $this->load->helper('url');
  $this->load->helper('form');
  $this->load->library('session');
  $this->load->dbforge();
  $this->load->library('email');
  $this->load->helper('url');
  $this->load->model('movies_model');
 }

 public function index()
 {
	$data['title']= "粉黑大作战";
	$data['heading'] = "对决啦！不是神作就是渣！";
	$this->db->where('status',1);
	$this->db->order_by("ID", "desc"); 
	$data['query'] = $this->db->get('movie');

	$data['query1'] = $this->db->get('movie_comments');
	
	$this->load->view('vote_view',$data);
 }
 
  public function addmovie()
 {
	$data['title']= "粉黑大作战";
	$data['heading'] = "添加新影片";
	
	if ($this->session->userdata('is_login'))
	{	
		$this->load->view('addmovie_view',$data);
		
	} else {
				$this->load->view('login_view',$data);
			}
			

 }
 
 
 function addmoive0(){
		$this->form_validation->set_rules("movieName","电影名",'required|min_length[1]|max_length[12]');
		 if (!$this->form_validation->run()){
				$this->addmovie()	;
											}	else {
													if ($this->movies_model->add()) {
													redirect('vote');
																					} else { $this->addmovie()	;}
													
													}
	}
	
function mcomment()
	{
		$data['title']= "粉黑大作战";
		$data['heading'] = "影片评论";
		$movieid = $this->uri->segment(3, 0);
		$data['movieid'] = $movieid;
		$data['moviename'] = $this->movies_model->movieName($movieid);
		$this->load->view('mcomment_view',$data);
	
	
	}
	
function addcomment()
		{
			
		$this->form_validation->set_rules("movieComment","movieComment",'required|min_length[5]|max_length[100]');
//		$this->form_validation->set_rules("password","password",'required|min_length[6]|max_length[12]');
		$this->movies_model->addMComment();
		$data1['title']= "粉黑大作战";
		$data1['heading'] = "影片评论";
		$data1['movieid']=  $this->input->post('movie_id');
		$data1['moviename'] =$this->input->post('movie_name');
		 
		 $this->load->view('mcomment_view',$data1);
		
		}
	
	
 public function follow()
 {
	$data['title']= "订餐";
	$data['heading'] = "跟团";
	$str=$_POST['eateryID'];
	$data['query'] = $this->db->get($str);
	$data['orderid'] = $_POST['orderid'];
	if ($this->session->userdata('username'))
	{	
		$this->load->view('follow_view',$data);
	} else {
				$this->load->view('login_view',$data);
			}
 }
 
 public function up()
 {
	
	$this->movies_model->up();
	
	redirect('vote');

	}
  public function down()
 {
	
	$this->movies_model->down();
	
	redirect('vote');

	}
   public function eq()
 {
	
	$this->movies_model->eq();
	
	redirect('vote');

	}
 
  public function insertorder()
 {
	
	$Name = $_POST['name'];
	$owner = $_POST['owner'];
	$eateryID = $_POST['eateryID'];
	$data = array('Name' => $Name, 'Owner' => $owner, 'eateryID'=>$eateryID);
	$str = $this->db->insert('order', $data);
	
	//$this->db->insert('porder','','','');
	
//	$cmd = "php index.php Pro message \"bfsun\"";
//	system($cmd);
   $fp  = fsockopen('localhost',80,$errno,$errstr,5);
 //  $fp  = fsockopen("www.example.com", 80, $errno, $errstr, 30);
    if(!$fp){
        echo "$errstr ($errno)<br />\n";
    }
 //   sleep(1);
    fputs($fp,"get /dinner/index.php/order/insertorderMail?order=1 \r\n"); #请求的资源 URL 一定要写对
    fclose($fp);
	
 

	redirect('order/index');

}

	public function insertorderMail()
	 {
			$orderID = $_GET['order'];
			$this->db->where('ID',$orderID);
			$orderResult = $this->db->get('order');
			$orderR = $orderResult->result();
			$owner = $orderR[0]->Owner;
			$eatery = $orderR[0]->eateryID;
			
			$user = $this->db->get('user');
			$email= array();
			foreach ($user->result() as $row){
				$email[$row->name] = $row->email;
				
				}
			
			$filename = "G:/xampp/htdocs/dinner/application/controllers/pro.txt";
	//		$somecontent = print_r($email);
			if (is_writable($filename)) {

	 
			if (!$handle = fopen($filename, 'a')) {
				 echo "不能打开文件 $filename";
				 exit;
			}

			foreach ($email as $row )
			{
				if (fwrite($handle, $row) === FALSE) {
					echo "不能写入到文件 $filename";
					exit;
				}
			}
			
			
			}

    }
	
	
	public function neweatery()
	{
		$data['title']= "订餐";
		$data['heading'] = "添加餐馆";
		$this->load->view('neweatery_viw',$data);
		
	}
		
	public function inserteatery()
	{
		$eatery = $_POST['eatery'];
		$data = array ('name' => $eatery);
		$this->db->insert('eatery', $data);
		
		$this->db->where('Name',$eatery);
		$eateryID = $this->db->get('eatery');
		
		$eateryID1 = $eateryID->result();
		$eateryIDN = $eateryID1[0]->ID;
		$query = "CREATE TABLE  `dinner`.`".$eateryIDN."` (`ID` INT( 16 ) NOT NULL AUTO_INCREMENT , `Name` VARCHAR( 128 ) NOT NULL , `Price` INT( 16 ) NOT NULL ,PRIMARY KEY (  `ID` )
) ENGINE = INNODB DEFAULT CHARSET = utf8";
		$this->db->query($query);
		redirect('order/index');
		}
	
	public function newmeal()
	{
		$data['title']= "订餐";
		$data['heading'] = "添加菜单";
		$data['query'] = $this->db->get('eatery');
		$this->load->view('newmeal1_view',$data);
		
		
	}
 
	public function newmeal1()
	{
		$data['title']= "订餐";
		$data['heading'] = "添加菜单";
		$data['eateryname'] = $_POST['name'];
		$data['eateryID'] = $_POST['eateryID'];
		$data['query'] = $this->db->get('eatery');
		$this->load->view('newmeal2_view',$data);
	}
	
	public function insertmeal()
	{
		$eateryID = $_POST['eateryID'];
		$name = $_POST['mealname'];
		$price = $_POST['mealprice'];
		$data = array ('Name' => $name, 'Price' => $price);
		$this->db->insert($eateryID, $data);
		redirect('order/index');
	}
 
	public function login()
	{
		$data['title']= "订餐";
		$data['heading'] = "登录";
		
		$this->load->view('login_view',$data);
	}
	public function logged()
	{
		$data['title']= "订餐";
		$data['heading'] = "登录";
		$username = $_POST['username'];
		$this->session->set_userdata('username',$username);
		
		redirect('order/index');
	}
	
	public function test()
	{
		$this->load->view('test');
	}
	
	public function getpost()
	{
		$id = intval($_POST['idBox']);
		$rate = $_POST['rate'];
		$this->db->where("ID",2);
		$data = array('' => $rate);
		$this->db->update("9", $data);
	}
	/*
	public function email()
	{
		$this->email->from('your@example.com', 'Your Name');
		$this->email->to('874874874@163.com'); 

		$config['protocol'] = 'smtp';
		smtp_host
		smtp_user
		smtp_pass
		smtp_port

		$this->email->initialize($config);
	$this->email->subject('Email Test');
	$this->email->message('Testing the email class.'); 

	$this->email->send();

echo $this->email->print_debugger();
	}
	*/
}
?>
