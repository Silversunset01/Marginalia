<nav class="navbar navbar-expand-sm bg-dark navbar-dark fixed-top pt-0 pb-0"> 
<a class="navbar-brand" href="AllNotes"><i class="fas fa-book-open"></i></a>
		<!-- collapse button -->
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
			<span class="navbar-toggler-icon"></span>
		</button>

	<!-- item -->
	<div class="collapse navbar-collapse" id="collapsibleNavbar">	
		<ul class="navbar-nav w-100">
		<span class="navbar-text smHide">|</span>
			<li class="nav-item">
				<a class="nav-link" href="new"><i class="far fa-file"></i><span class="smShow"> New Note</span></a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="edit?NB=<?php echo $_GET['NB']?>&ID=<?php echo $_GET['ID']?>&Title=<?php echo $_GET['Title']?>"><i class="far fa-edit"></i><span class="smShow"> Edit</span></a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="settings"><i class="fas fa-cog"></i><span class="smShow"> Settings</span></a>
			</li>	
			<li class="nav-item">
				<a class="nav-link" href="#" id="toggleSearch"><i class="fas fa-filter"></i><span class="smShow"> Toggle Filter</span></a>
			</li>
			<!-- Search Box -->
			<form class="form-inline">
				<input class="form-control form-control-sm" type="text" placeholder="Filter Tables" id="myInput" style="display:none; margin-right: 10px;">
			</form>
				
		<span class="navbar-text smHide">|</span>
		
		<!-- Notebooks -->
		<?php
			$mb = $db->prepare("SELECT * FROM Notes WHERE owner = ? AND Trash='0' ORDER BY notebook ASC");
			$mb->bind_param("s", $_SESSION['username']);
			$mb->execute();
			$mbresult = $mb->get_result();
			$nbList = [];
			
			//Create List of Notebooks
			if (empty($_GET['NB'])){
				$nbTitle = "Select a Notebook";
			} else {
				$nbTitle = "<b>Notebook:</b> ".$_GET['NB'];
			}
			echo '<li class="nav-item dropdown"> <a class="nav-link dropdown-toggle" href="#" id="navbardrop" data-toggle="dropdown">'.$nbTitle.'</a>';
			echo '	<div class="dropdown-menu">';
			
			while ($mbrow = $mbresult->fetch_array()){
				array_push($nbList, $mbrow['notebook']);
				$nbList = array_unique($nbList);
			}

			foreach ($nbList as $nb){
				echo '		<a class="dropdown-item" href="main?NB='.$nb.'"> <i class="fas fa-book-open"></i> '.$nb.'</a>';
			}

			echo '	</div>';
			echo '</li>';
			
			
			//Create List of Notes
			$mb2 = $db->prepare("SELECT * FROM Notes WHERE owner = ? AND Notebook = ? AND Trash='0' ORDER BY Tag ASC, Title ASC");
			$mb2->bind_param("ss", $_SESSION['username'], $_GET['NB']);
			$mb2->execute();
			$mb2result = $mb2->get_result();

			if (empty($_GET['ID'])){
				$noteTitle = "Select a Note";
			} else {
				$noteTitle = "<b>Note:</b> ".$_GET['Title'];
			}
			

			echo '<li class="nav-item dropdown"> <a class="nav-link dropdown-toggle" href="#" id="navbardrop" data-toggle="dropdown">'.$noteTitle.'</a>';
			echo '	<div class="dropdown-menu">';

			while ($mb2row = $mb2result->fetch_array()){
				switch ($mb2row['Tag']) {
					case 'caldate': $tag = '<i class="far fa-calendar-alt"></i>';
						break;
					case 'computer': $tag ='<i class="fas fa-laptop"></i>';
						break;
					case 'code': $tag ='<i class="fas fa-code"></i>';
						break;
					case 'journal': $tag = '<i class="fas fa-book"></i>';
						break;
					case 'folder': $tag = '<i class="far fa-folder-open"></i>';
						break;
					case 'flag': $tag = '<i class="far fa-bookmark"></i>';
						break;
					case 'web': $tag = '<i class="fas fa-globe"></i>';
						break;
					case 'tasks': $tag = '<i class="far fa-check-square"></i>';
						break;
					default: $tag = '';
				}
					
				echo '		<a class="dropdown-item" href="main?NB='.$_GET['NB'].'&ID='.$mb2row['ID'].'&Title='.$mb2row['Title'].'">'.$tag.' '.$mb2row['Title'].'</a>';
			}

			echo '	</div>';
			echo '</li>';				
			$mb2->close();
		?>
		
	  </ul>
	  </div>
 </nav>