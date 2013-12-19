<html>
<head>
<meta charset="utf-8">
<title ><?=$title?></title>
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.3/css/bootstrap.min.css">
<style type="text/css">
img.back {

		position:fixed;
		left:20%;
		top:0%;

		}


ol.one
{
position:fixed;
left:30%;
top:30%;
}



</style>

</head>
<body>

<h1 class ="back"><?=$heading?></h1>

<img class ="back" src="http://opcdn.battle.net/static/heroes/429/images/media/wallpaper/heroes001/facebook.jpg" />

<div class="one">
<ol class="one" >
<?php
		
		if ($this->session->userdata('is_login'))
			{	
				echo $this->session->userdata('username').anchor('account/logout','退出|');
				echo anchor ("vote/addmovie",'添加新片').'</p>';
			}	else {	echo "尚未登录！";
						echo anchor ("account", "登录");
						echo "|";		
						echo anchor ("account/reg", "注册");						
						}		
		
		echo "</br>";
		echo '<table border="1">';
		echo "<tr><th>ID</th><th>电影电视名</th><th>得分</th><th>票数</th>";
		echo "<th>赞？渣？</th> <th>评论数</th>";
		echo "</tr>";
		foreach($query->result() as $row)
        {        
			echo "<tr><td>$row->ID </td><td><ins>".anchor("vote/mcomment/$row->ID",$row->Name)."</ins></td><td> $row->Results</td><td> $row->vcount</td>";
			$c = $row->comments;
			/*
			echo "<tr><td>$row->ID </td><td>";
			echo form_open('vote/mcomment');
			echo form_submit('movieName' ,$row->Name);
			echo form_close();
					echo "</td><td> $row->Results</td><td> $row->vcount</td>";
			*/
	

			
			if ($this->session->userdata('is_login') )
			{
				$movieid = $row->ID;
				$userid = $this->session->userdata('userid');
				$this->db->where('movieID',$movieid);
				$this->db->where('userID',$userid);
				$query = $this->db->get('movie_vote');
				$q =$query->row();
				if ($query->num_rows() == 0)				
					{	
						echo "<td>";
						echo '<table border="1">';
						echo form_open('vote/up/');
						echo form_hidden('movieID', $row->ID);	
						echo '<input type="submit" value="神作！">';
						echo '</form>';
						
						echo form_open('vote/eq/');
						echo form_hidden('movieID', $row->ID);	
						echo '<input type="submit" value="酱油！">';
						echo '</form>';
						
						echo form_open('vote/down/');
						echo form_hidden('movieID', $row->ID);
						echo '<input type="submit" value="垃圾！">';
						echo '</form>';
						echo '</table>';
						echo '</td>';
						
					}	else {	echo "<td>已出招</td>";}
			}	else {	echo "<td>登录再战吧</td>";}
			echo "<td>".$c."</td>";
		}
		
		echo "</tr>";
		echo "</table>";
	
 ?>


</ol>
</div>
</body>
</html>
