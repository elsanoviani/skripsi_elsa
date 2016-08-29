<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once APPPATH.'libraries/twitteroauth.php';
include_once APPPATH.'libraries/simple-html-dom/simple_html_dom.php';

class Tw extends CI_Controller {

	public function __construct() {
		parent::__construct();
		
		$this->load->model('Twitter_model');
		$this->load->library('simple-html-dom/simple_html_dom');
	}

	public function index() {
		$key = 'zHsogYskkLB3SZd6d1IzmKH7U';
		$secret_key = '2wvGz0UdTDIVZqPhaP7j8PF65gLWN5kYCqboNomq9HYJTxQPxb';
		$token = '1411914864-KyTD8WgewNJXqV9348LViirYKugmGVhkx4F3bY4';
		$secret_token = 'UQMqk9JmfK9lUVAddDoadtfNGsDan9Pyx7gBbhnA3Lbwu';

		$screen_name = @$_GET['screen_name'];
		$screen_name = !empty($screen_name) ? $screen_name  : null;

		$response = array();
		$status = !empty($screen_name) ? true : false;

		$list_username = array(
			'0'=>'- pilih akun -',
			'kompascom'=>'Kompas.com',
			'Yusuf_Mansur'=>'Yusuf Mansur',
			'detikcom'=>'detikcom',
			'liputan6dotcom'=>'Liputan6.com',
			'republikaonline'=>'Republika.co.id',
			'okezonenews'=>'Okezone',
			'officialRCTI'=>'RCTI Official',
			'TRANSTV_CORP'=>'TRANS TV',
			'whatsonANTV'=>'ANTV',
			'VIVAnews'=>'VIVAnews'
		);

		$conn = new TwitterOAuth($key, $secret_key, $token, $secret_token);

		if ($status == true) {
			$response = $conn->get('statuses/user_timeline', array('count'=>1, 'screen_name'=>$screen_name));

		}

		$request = array(
			'screen_name'=>$screen_name,
			'list_username'=>$list_username,

			'response'=>$response,
			'status'=>$status,
			'data_twitter'=>array();
		);
		
		// echo '<pre>';
		// print_r($response);
		// echo '</pre>';

		if (count($response) > 0) {
			foreach ($response as $row) {
				$user_id = $row->user->id;
				$user = $row->user->screen_name;
				$tweet = $row->text;
				$date = date('Y-m-d h:i:s', strtotime($row->created_at));
				$link = isset($row->entities->urls[0]->url) ? $row->entities->urls[0]->url : null;

				$source = null;
				if (!empty($link)) {
					$data=$this->curlget($link);//http://ow.ly/4wCZd
					preg_match_all("|Location: (.*)\r\n|", $data, $hasil);
					$jumlah=count($hasil[1]);
					$source = $hasil[1][$jumlah - 1];
				}
				
				$result = $this->grab_news($source);
				$title = $result['title'];
				$content = $result['content'];
				
				array_push($request['data_twitter'], array(
					'user_id'=>$user_id,
					'portal_berita'=>$user,
					'date_tweet'=>$date,
					'tweet_berita'=>$tweet,
					'url_berita'=>$link,
					'source'=>$source,
					'title'=>$title,
					'content'=>$content,
					
					'tes'=>'Hello'
				));
			
				// ehck data agar tidak duplicate
				$model = $this->Twitter_model->checked($user_id, $date);
				if (count($model) == 0) {
					$datas = array(
						'user_id'=>$user_id,
						'portal_berita'=>$user,
						'date_tweet'=>$date,
						'tweet_berita'=>$tweet,
						'url_berita'=>$link
					);
					
					if (!empty($source)) { 
						$datas['source'] = $source;
					 }

					$this->Twitter_model->insert($datas);
				}
			}
		}

		// $this->load->view('twitter/index', $request);
	}
	
	private function curlget($url = null) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_NOBODY, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER ,1);

		$data=curl_exec($ch);
		curl_close($ch);
		return $data;
	}
	
	private function grab_news($url = null) {
		$url = strtolower($url);
		$result = array('title'=>null, 'content'=>null);

		if (!empty($url)) {
			$html = file_get_html($url);
			if (strpos($url, 'kompas')) {
				$title = $html->find('div[class="kcm-read-top"]', 0)->find('h2', 0)->plaintext;
				$title = isset($title) ? $title : null;

				$content = $html->find('div[class="kcm-read-body"]', 0)->find('div[class="span6"]', 0)->find('div[class="kcm-read-text-wrap"]', 0)->find('div[class="kcm-read-text"]', 0)->plaintext;
				$content = isset($content) ? $content : null;
				
				$result = array('title'=>$title, 'content'=>$content);
			} elseif (strpos($url, 'detik')) {
			
			} elseif (strpos($url, 'a')) {
			
			} elseif (strpos($url, 'b')) {
			
			} elseif (strpos($url, 'c')) {
			
			} elseif (strpos($url, 'd')) {
			
			}
		}
		
		return $result;
	}
}

