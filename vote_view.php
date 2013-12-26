<html>
<head>
<meta charset="utf-8">
<title ><?=$title?></title>
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.3/css/bootstrap.min.css">
<link rel="stylesheet" href="http://www.orzwow.com/css/my.css">


</head>
<body>
<h1><?=$heading?></h1>
<div align=center>
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
?>						
</div>
<ol class = one>
<?php
		
		
		
		echo "</br>";
		echo '<table border="1">';
		echo "<tr><th>电影电视名</th><th>得分</th><th>票数</th>";
		echo "<th>赞？渣？</th> <th>评论数</th>";
		echo "</tr>";
		foreach($query->result() as $row)
        {        
			echo "<tr><td><ins>".anchor("vote/mcomment/$row->ID",$row->Name)."</ins></td><td> $row->Results</td><td> $row->vcount</td>";
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

<ol class = two>
<?php
		
		echo "</br>";
		echo '<table border="1" >';
		echo '<tr><th>评论</th><th class="one">作者</th>';
		echo "</tr>";
		foreach($query1->result() as $row)
        {        
			echo "<tr><td><ins>".anchor("vote/mcomment/$row->movie_id",$row->comments)."</ins></td><td> $row->author</td>";
	
		}
		
		echo "</tr>";
		echo "</table>";
	
 ?>


</ol>

</body>
</html>