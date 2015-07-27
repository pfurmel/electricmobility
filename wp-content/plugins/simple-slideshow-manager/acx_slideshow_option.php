<?php 
$error_message="";
$error_message_gallery_true = '';
/* create array for save all image data */	
if(get_option('acx_slideshow_imageupload_complete_data') == "")
{	 
	$acx_slideshow_imageupload_complete_data = array(
													 "default" => array()	
													);
	update_option('acx_slideshow_imageupload_complete_data',serialize($acx_slideshow_imageupload_complete_data));
}	
/*create array for save all image data */
	else
	{
		$acx_slideshow_imageupload_complete_data=unserialize(get_option('acx_slideshow_imageupload_complete_data'));
	}
/* if gallery data is null insert into an array */

	if(get_option('acx_slideshow_gallery_data') == "")
	{	 
		$acx_slideshow_gallery_data = array(
												 "default" => array()	
												);
		update_option('acx_slideshow_gallery_data',serialize($acx_slideshow_gallery_data));
	}
	else
	{
		$acx_slideshow_gallery_data=unserialize(get_option('acx_slideshow_gallery_data'));
	}
$acx_slideshow_misc_hide_advert = get_option('acx_slideshow_misc_hide_advert');
if($acx_slideshow_misc_hide_advert == "")
{
$acx_slideshow_misc_hide_advert = "no";
}

/* Form data sent starts */
	if(ISSET($_POST['my_plugin_hidden']))
	{
		$acx_form_data_y = $_POST['my_plugin_hidden'];
	}
	else
	{
		$acx_form_data_y = '';
	}
	
	if($acx_form_data_y == 'Y') 
	{	
	if(is_serialized($acx_slideshow_imageupload_complete_data))
	{
		$acx_slideshow_imageupload_complete_data=unserialize(get_option('acx_slideshow_imageupload_complete_data'));
	}
	if($acx_slideshow_imageupload_complete_data=="")
	{
		$acx_slideshow_imageupload_complete_data=array();
	}
		$acx_slideshow_galleryname = trim($_POST['acx_slideshow_galleryname']);
		
		
		if (preg_match('/[\'^£$%`!&*()}{@#~?><>,|=_+¬-]/', $acx_slideshow_galleryname))
		{
			// one or more of the 'special characters' found in $string
						$acx_slideshow_galleryname = "";
		}
		
		if($acx_slideshow_galleryname=="")
		{
$error_message = 'Please Enter A Valid Name';
		}
		foreach ($acx_slideshow_imageupload_complete_data as $key => $value)
		{
			if($acx_slideshow_galleryname == $key)
			{
$error_message = 'Gallery Name Already Exist, Add Another One';
			}
		}
		if($acx_slideshow_galleryname!="")
		{
			$acx_slideshow_galleryname = trim($acx_slideshow_galleryname);
			if (!array_key_exists($acx_slideshow_galleryname,$acx_slideshow_imageupload_complete_data))
			{
				$number_of_galleries = count($acx_slideshow_imageupload_complete_data);
				if($number_of_galleries == "") { $number_of_galleries = 0; }
				$acx_slideshow_imageupload_complete_data[$acx_slideshow_galleryname] = array();
$error_message_gallery_true = 'Gallery "'.$acx_slideshow_galleryname.'" Added Successfully';
			}
		}
		update_option('acx_slideshow_imageupload_complete_data',serialize($acx_slideshow_imageupload_complete_data));
?>
<?php
	}
/* Form data sent ends */

/* Normal page display starts */
	else
	{
	
		$acx_slideshow_galleryname = array();
		$acx_slideshow_imageupload_complete_data=unserialize(get_option('acx_slideshow_imageupload_complete_data'));
		//---------------- check version and update type
		$acx_slideshow_version = get_option('acx_slideshow_version');
		if($acx_slideshow_version < '2.1') // Current Version
		{
			$acx_slideshow_version = '2.1'; // Current Version
			foreach($acx_slideshow_imageupload_complete_data as $key=>$values)
			{
					$i=0;
				foreach($values as $value)
				{
					if($value['type']=="")
					{
						$value['type'] = "image";
						$acx_slideshow_imageupload_complete_data[$key][$i] = $value;
					}
					$i=$i+1;
				}
			}
			update_option('acx_slideshow_imageupload_complete_data',serialize($acx_slideshow_imageupload_complete_data));
			update_option('acx_slideshow_version',$acx_slideshow_version);
		}
/* check version and update type */
	}
	
/* Normal page display ends */

/* Delete Gallery Option */
    if (isset($_GET['del'])) 
	{
       $acx_del_gallery_name = $_GET['del'];
		if($acx_del_gallery_name != "")
		{
		$acx_slideshow_gallery_data = unserialize(get_option('acx_slideshow_gallery_data'));
			unset($acx_slideshow_imageupload_complete_data[$acx_del_gallery_name]);
			unset($acx_slideshow_gallery_data[$acx_del_gallery_name]);
			update_option('acx_slideshow_imageupload_complete_data',serialize($acx_slideshow_imageupload_complete_data));
			update_option('acx_slideshow_gallery_data',serialize($acx_slideshow_gallery_data));
		}
    }
/* MAIN PAGE DISPLAY */
if(is_serialized($acx_slideshow_imageupload_complete_data))
	{
	$acx_slideshow_imageupload_complete_data=unserialize(get_option('acx_slideshow_imageupload_complete_data'));
	}
if($acx_slideshow_imageupload_complete_data=="")
{
	$acx_slideshow_imageupload_complete_data=array();
}
?>
<div class="wrap">
<?php
echo "<h2>" . __( 'Simple Slideshow Manager Settings', 'simple-slideshow-manager' ) . "</h2>"; 
?>	
<form name="acurax_si_form" method="post" onSubmit="javascript:return acx_validate_newgallery()">
		<input type="hidden" name="my_plugin_hidden" value="Y">
		<p class="widefat" style="padding:8px;width:99%;margin-top:8px;">	<?php _e('New Gallery Name','simple-slideshow-manager' ); ?>
			<input type="text" autocomplete="off" name="acx_slideshow_galleryname" id='acx_slideshow_galleryname' value="" size="60" onclick="javascript:hide_validate_span()"/>
			<input type="button" name="acx_slideshow_addgallery"  value="<?php _e('Add Gallery', 'simple-slideshow-manager') ?>" id="acx_ex_add_gallery" class="button" onclick="javascript:acx_validate_newgallery()"/>
			<br>
			<span id='acx_validate_galleryname' style='display:none'></span>
			<span id='acx_validate_galleryname_2' style='display:none'></span>
		</p>
<?php
if($error_message!="")
{ 
?>
<script>
document.getElementById('acx_validate_galleryname').style.display='block';
document.getElementById("acx_validate_galleryname").style.marginLeft="200px";
document.getElementById('acx_validate_galleryname').innerHTML='<?php echo $error_message; ?>';
setTimeout(function(){
jQuery('#acx_validate_galleryname').fadeOut('slow');
}, 5000);
</script>
<?php 
}

if($error_message_gallery_true !==0)
{
?>
<script>
document.getElementById('acx_validate_galleryname_2').style.display='block';
document.getElementById("acx_validate_galleryname_2").style.marginLeft="200px";
document.getElementById("acx_validate_galleryname_2").style.fontSize = "15px";
document.getElementById("acx_validate_galleryname_2").style.color = "#0d6e00";
document.getElementById("acx_validate_galleryname_2").innerHTML='<?php echo $error_message_gallery_true; ?>';
setTimeout(function(){
jQuery('#acx_validate_galleryname_2').fadeOut('slow');
}, 5000);
</script>
<?php
}
?>
<!-- gallery name validation code start's here -->
		<script>
		
		function acx_validate_newgallery()
		{
				var val = document.getElementById('acx_slideshow_galleryname').value;
				var val = val.trim();
				var name_pattern=/^[a-zA-Z0-9 ]{0,100}$/i;

				if(val=='')
				{
					document.getElementById('acx_validate_galleryname').style.display='block';
					document.getElementById("acx_validate_galleryname").style.marginLeft="250px";
					document.getElementById('acx_validate_galleryname').innerHTML='Name Should Not Be Empty';
					return false;
				}
				else if(name_pattern.test(val))
				{
					document.acurax_si_form.submit();
				}
				else 
				{
					document.getElementById('acx_validate_galleryname').style.display='block';
				    document.getElementById("acx_validate_galleryname").style.marginLeft="220px";
				    document.getElementById('acx_validate_galleryname').innerHTML='Special Characters Are Not Allowed Here';
				    return false;
				}			
		}
		
		function hide_validate_span()
		{
			document.getElementById('acx_slideshow_galleryname').value ='';
			jQuery('#acx_validate_galleryname').fadeOut('slow');
		}
		
	</script>
<!--  gallery name validation code end's here   -->
	
     <div class="widefat" style="padding:8px;width:99%;margin-top:8px;">
		<h4 style="padding:5px;margin:3px;"><?php _e('Existing Galleries','simple-slideshow-manager'); ?></h4>
<?php			
		echo "<ul id='acx_ex_gall' style='display:inline-block;'>";
			foreach ($acx_slideshow_imageupload_complete_data as $key => $value)
			{
				echo "<li><div class='acx_left'>";
				echo $key;
				?>
		        </div><div class="acx_right"> 
				<a id="acx_slideshow_delete_gallery" class="del_gallery button" href="?page=Acurax-Slideshow-Settings&del=<?php echo $key; ?>" alt = "Click to Delete this Gallery" onclick="return confirm('Are you sure? \n\nNOTE: You Cant Undo This Action Once Processed');"><?php _e('Delete this Gallery','simple-slideshow-manager'); ?></a>
				<a id="acx_slideshow_manage_gallery" class="manage_gallery button"  value = "<?php echo $key?>" href="admin.php?page=Acurax-Slideshow-Add-Images&name=<?php echo $key;?>" alt = "Click to Manage this Gallery"><?php _e('Manage this Gallery','simple-slideshow-manager'); ?></a></br></br>
				<?php
				echo "</div></li>";			
			}
			echo "</ul>";
?>		</div>
	</form>
<?php

if(ISSET($_GET['status']))
{
$acx_get_status_update = $_GET['status'];
}
else
{
$acx_get_status_update = '';
}

if($acx_get_status_update =="updated")
{
	?>
	<div id="acx_slideshow_updation_notice" name="acx_slideshow_updation_notice">
	<?php _e('You have successfully completed the updating processs','simple-slideshow-manager'); ?>
	<a name="updated"></a>
	</div>
	<?php
}
?>
<hr/>
<?php
if($acx_slideshow_misc_hide_advert == "no")
{
acx_slideshow_comparison(1); 
} 
?>
<br>
	<p class="widefat" style="padding:8px;width:99%;">
		Something Not Working Well? Have a Doubt? Have a Suggestion? - <a href="http://www.acurax.com/contact.php" target="_blank">Contact us now</a> | Need a Custom Designed Theme For your Blog or Website? Need a Custom Header Image? - <a href="http://www.acurax.com/contact.php" target="_blank">Contact us now</a>
	</p>
</div>