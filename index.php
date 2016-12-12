<?php
session_start("social");
	function mycount($current){
		$done = false;
		$i = 27;
		$arr= str_split($current);
		$arr[$i] = chr(ord($arr[$i])+1);
		
		while(ord($arr[$i])>90 && $i>0){
			$arr[$i] = 'A';
			$arr[$i-1] = chr(ord($arr[$i-1])+1);
			$i--;
		}
		$output = '';
		for($i = 0; $i<28;$i++){
			$output .=$arr[$i]; 
		}
		return $output;
	}

	$data = null;
	$result = null;
	$profile = filter_input(INPUT_GET,'profile',FILTER_SANITIZE_STRING);
	$_SESSION['profile'] = $profile;
	if(filter_input(INPUT_GET,'profile',FILTER_SANITIZE_STRING)){ 
		$isOwner = false;
		$host = 'localhost';
                $username = 'admin172';
            	$pass = 'hB1lT^F+,O9T';
            	$database = 'ipsocial';
		$conn = new mysqli($host, $username, $pass, $database);
		$key = null;
                $user = $_SESSION['user'];
               
		if($user==filter_input(INPUT_GET,'profile',FILTER_SANITIZE_STRING)) {
			$result = $conn->query("SELECT loginSalt FROM users WHERE Username ='".$_SESSION['user']."'");
			if($result == false){
				echo "<script>alert('Please Log in');window.location='https://www.innovativeprogramming.org/social/';</script>";
				
			}else{
				$key = hash('sha256',hash('sha256', $user).$result->fetch_assoc()['loginSalt']);
			}
			if($key == $_SESSION['key']){
				$isOwner = true;
			}else{
				echo "<script>alert('Please Log in again');window.location='https://www.innovativeprogramming.org/social/';</script>";
			}
	
		}
		$result = $conn->query("SELECT * FROM profile WHERE Username='".$profile."'");
		$data = $result->fetch_assoc();
		$result = $conn->query("SELECT * FROM profile WHERE Username='".$_SESSION['user']."'");
		$userData = $result->fetch_assoc();
		$bMonth = '';
		$bDay = '';
		switch(substr($data['Birthday'],-5,2)){
			case '01': 
				$bMonth = 'January';
				break;
			case '02': 
				$bMonth = 'February';
				break;	
			case '03': 
				$bMonth = 'March';
				break;
			case '04': 
				$bMonth = 'April';
				break;
			case '05': 
				$bMonth = 'May';
				break;
			case '06': 
				$bMonth = 'June';
				break;	
			case '07': 
				$bMonth = 'July';
				break;
			case '08': 
				$bMonth = 'August';
				break;	
			case '09': 
				$bMonth = 'September';
				break;
			case '10': 
				$bMonth = 'October';
				break;	
			case '11': 
				$bMonth = 'November';
				break;
			default: 
				$bMonth = 'December';
				break;		
		
		}
		if(substr($data['Birthday'],-2,1) =='0'){
			$bDay = substr($data['Birthday'],-1);
		
		}else{
			$bDay = substr($data['Birthday'],-2);
		}
		
		switch(substr($data['Birthday'],-1)){
			case '1': 
				$bDay .= 'st';
				break;
			case '2': 
				$bDay .= 'nd';
				break;	
			case '3': 
				$bDay .= 'rd';
				break;
			default:
			 	$bDay .= 'th';
				break;
		}
	
	}else{
		if($user != null){
			echo "<script>alert('Profile Does Not Exist');window.location='https://www.innovativeprogramming.org/social/profile=".$user."';</script>";
			
		}else{
			echo "<script>alert('Please Log in');window.location='https://www.innovativeprogramming.org/social/';</script>";
			
		}
	}
	

	
?>

<!DOCTYPE html>
<html lang='en-US'>
    <head>
        <title><?php echo $data['First_Name']."'s"; ?> page</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link type="text/css" rel="stylesheet" href='https://www.innovativeprogramming.org/social/resources/social.css' />
        <link rel="shortcut icon" type="image/x-icon" href="https://www.innovativeprogramming.org/social/resources/favicon.ico" />
    </head>
    <body>
    	
        <script src='https://www.innovativeprogramming.org/social/resources/jquery.js'></script>
        <script src='https://www.innovativeprogramming.org/social/resources/profileManager.js'></script>
        
        <nav class='social'>
            <img class='navbar' alt= 'world' src='https://www.innovativeprogramming.org/social/resources/world.png'>
                <h1 class='title'>Social Network</h1>
                
            <div class='search'>
                <form class='search' method='POST'>
                    <input onclick='searchControl(this)' class='search' value='Search' type='search' name='search'>
                    <input class='searchGO' type='submit' value='GO'>
                </form>
            </div>
                <img class='notifications' alt='note' src='https://www.innovativeprogramming.org/social/resources/notifications.png'>
                <img class='account' alt='acc' src='https://www.innovativeprogramming.org/social/resources/account.png'>
                <img class='chat' alt='chat' src='https://www.innovativeprogramming.org/social/resources/chat.png' onclick='openChat()'>
                <div id='userIcon' class='user'>
                    <img class='user' alt='?' <?php echo "src='https://www.innovativeprogramming.org/social/users/".$userData['Picture']."'"; ?>>
                    <h3 class='user'><?php echo $userData['First_Name']; ?> </h3>
                </div>
        </nav>
        
        <main class='profile'>
            <div class='main'>
            <div class='profile'>
                <h2 class='name'><?php echo $data['First_Name']." ".$data['Last_Name'];?></h2>
                <img class='profile' alt='Missing_Picture' <?php echo "src='https://www.innovativeprogramming.org/social/users/".$data['Picture']."'"; ?> >
                <div class='description'>
                    <p id='bDay' class='description'>Born on <?php  echo $bMonth." ".$bDay; ?></p>
                    <!--<p id='origin' class='description'>From Queens, New York</p>
                    <p id='home' class='description'>Lives in Dallas, Texas</p>
                    <p id='job' class='description'>Works at Home</p>
                    <p id='education' class='description'>Graduated From X School on 1995</p>
                    <p id='relationship' class='description'>Divorced</p>-->
                </div>
                <div class='details'>
                    <div class='hobbies'>
                        <h3 class='details'>Hobbies</h3>
                        <p class='hobbies'><?php echo $data['Hobbies'];?></p>
                    </div>
                    <div class='bio'>
                        <h3 class='details'>Biography</h3>
                        <p class='bio'><?php echo $data['Bio'];?></p>
                    </div>
                </div>
            </div>
                <div class='friends'>
                    <div class='status'>
                        <svg height='10' width='10' id='statusDot'>
                            <circle cx="5" cy="5" r="5" stroke="green" stroke-width="0" fill="green" />
                        </svg>
                        <p class='status'>Contacts Online: 0</p>
                    </div>
                    <div class='contact'>
                        <svg height='10' width='10' class='contact'>
                            <circle cx="5" cy="5" r="5" stroke="green" stroke-width="0" fill="green" />
                        </svg>
                        <label class='contact'>Online Friend</label>
                    </div>
                    <div class='contact'>
                        <svg height='10' width='10' class='contact'>
                            <circle cx="5" cy="5" r="5"  fill="#787878" />
                        </svg>
                        <label class='contact'>Offline Friend</label>
                    </div>
                </div>
            </div>
		
            <div class='posts'>
                <div class='poster'>
                    <img class='poster' alt='Missing_Picture' <?php echo "src='https://www.innovativeprogramming.org/social/users/".$data['Picture']."'"; ?>>
                    <form id='poster' class='poster' method='POST' action='https://www.innovativeprogramming.org/social/profile/post.php'>
                        <textArea name='post' class='poster'></textArea>
                        <input id='pSubmit' type='submit' value='Post'>
                    </form>
                </div>
                <?php
                	$host = 'localhost';
                	$username = 'admin172';
            		$pass = 'hB1lT^F+,O9T';
            		$database = 'ipsocialpost';
			$conn = new mysqli($host, $username, $pass, $database);
			$database = 'ipsocial';
			$conn2 = new mysqli($host, $username, $pass, $database);
                	$results = $conn->query("SELECT * FROM ".$profile." ORDER BY TIME DESC LIMIT 10");
                	while($out = $results->fetch_assoc()){
                		$auth = $conn2->query("SELECT * FROM profile WHERE Username='".$out['Author']."'")->fetch_assoc();
                		
                	echo "<div class='post' >
                    <a class='name' href=https://www.innovativeprogramming.org/social/profile/index.php?profile=".$out['Author']."'><h3 class='name'>".$auth['First_Name']." ".$auth['Last_Name']."</h3></a>
                    <p class='timestamp'>".$out['Time']."</p>
                    <div onclick='openPostSettings(this)'><img class='pSetting' alt='?' src='https://www.innovativeprogramming.org/social/resources/settings.png'>
                        
                    </div>
                    <img class='post' alt='Missing_Picture' src='https://www.innovativeprogramming.org/social/users/".$auth['Picture']."'>
                    <div class='pContent'>
                        <p class='post'>".$out['Content']."</p>
                    </div>
                    <div class='interact'>
                        <label class='counter'>".$out['Score']."</label>
                        <div class='good'>
                            <img class='good' alt='good' src='https://www.innovativeprogramming.org/social/resources/good.png'>
                            <label class='good'>Like</label>
                        </div>
                        <button class='bad'>
                            <img class='bad' alt='bad' src='https://www.innovativeprogramming.org/social/resources/bad.png'>
                            <label class='bad'>Dislike</label></button>
                        
                        <div class='reply'>
                            <img class='reply' alt='reply' src='https://www.innovativeprogramming.org/social/resources/reply.png'>
                            <label class='reply'>Reply</label>
                        </div>
                    </div>
                </div>";
                	
                	}
                	
                	
                	
                ?>
                
                
                
            </div>
            
        </main>
        <div id='sidebar'>
        
        </div>
    </body>
</html>
