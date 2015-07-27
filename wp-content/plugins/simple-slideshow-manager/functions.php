<?php
/* get all data from database */
$acx_slideshow_imageupload_complete_data=unserialize(get_option('acx_slideshow_imageupload_complete_data'));
$acx_slideshow_gallery_data = unserialize(get_option('acx_slideshow_gallery_data'));
$acx_slideshow_misc_user_level = get_option('acx_slideshow_misc_user_level');

if($acx_slideshow_misc_user_level=="")
{
	$acx_slideshow_misc_user_level = "manage_options";
}
function acx_slideshow_js() 
{
if(function_exists('wp_enqueue_media'))
{
	wp_enqueue_media();	
}	
?>
<script type="text/javascript">
function acx_slideshow_media_upload_loader()
{
var custom_uploader;
jQuery(acx_slideshow_upload_image_button).click(function(e) 
{
e.preventDefault();
//If the uploader object has already been created, reopen the dialog
if (custom_uploader) 
{
custom_uploader.open();
return;
}
//Extend the wp.media object
custom_uploader = wp.media.frames.file_frame = wp.media({
title: '<?php __('Choose Image','simple-slideshow-manager') ?>',
button: {
text: '<?php _e('Choose Image','simple-slideshow-manager') ?>'
},
multiple: false						});
//When a file is selected, grab the URL and set it as the text field's value
custom_uploader.on('select', function() 
{
attachment = custom_uploader.state().get('selection').first().toJSON();
var acx_slideshow_upload_image_url="#acx_slideshow_upload_image_url";
jQuery(acx_slideshow_upload_image_url).val(attachment.url);
var acx_slideshow_title="#acx_slideshow_title";
jQuery(acx_slideshow_title).val(attachment.title);
var acx_slideshow_caption="#acx_slideshow_caption";
jQuery(acx_slideshow_caption).val(attachment.caption);
var acx_slideshow_alttext="#acx_slideshow_alttext";
jQuery(acx_slideshow_alttext).val(attachment.alt);
var acx_slideshow_desc="#acx_slideshow_desc";
jQuery(acx_slideshow_desc).val(attachment.description);
upload_image();
});
//Open the uploader dialog
custom_uploader.open();
});
}

</script>
<?php
} 
add_action('admin_head', 'acx_slideshow_js'); 

function acx_slideshow_jquery()
{
	wp_enqueue_script( 'jquery' );
}
add_action( 'wp_enqueue_scripts', 'acx_slideshow_jquery' );
add_action('admin_enqueue_script','acx_slideshow_jquery');
function acx_slideshow_jquery_ui_sortable()
{
	wp_enqueue_script('jquery-ui-sortable');
	
}
add_action('admin_enqueue_script','acx_slideshow_jquery_ui_sortable');

function acx_slideshow_admin_style()  // Adding Style For Admin
{
echo '<link rel="stylesheet" type="text/css" href="' .plugins_url('style.css', __FILE__). '">';
}
add_action('admin_head', 'acx_slideshow_admin_style'); // ADMIN

function acx_slideshow_css()
{
wp_enqueue_style ( 'my_plugin2_style', plugins_url('style.css', __FILE__) );
}
add_action( 'wp_print_styles', 'acx_slideshow_css' );

function acx_slideshow_api_js()
{
?>
<script type="text/javascript" id="acx_js_api">
function call_acx_y_player(frame_id, func,id,u_id, args)
{
frame_id_dpl = frame_id+u_id;
var frame ='#'+frame_id+u_id+id;
var frame_id_yt='#'+frame_id+u_id+'_frame_'+id;
var imageid = '#acx_image_'+u_id+'_'+id;
var vedio_stat_field ='#acx_hidden_id_'+u_id;
var palybuttn = '.acx_dis_yplay_but_'+u_id;
var pausebuttn = '.acx_dis_ypause_but_'+u_id;
var newvalue = 0;
if(func=="playVideo")
{
	
var img_yt_thumbnail_element = ".acx_ssm_yt_"+u_id+"_"+id;
var img_yt_thumbnail_h = jQuery(img_yt_thumbnail_element).height();  
var img_yt_thumbnail_w = jQuery(img_yt_thumbnail_element).width();  

var img_stop = '.img_stop_'+u_id;
var img_play = '.img_play_'+u_id;

jQuery(img_stop).hide();
jQuery(img_play).hide();


var img_prev = '.img_prev_'+u_id;
var img_next = '.img_next_'+u_id;

jQuery(img_prev).hide();
jQuery(img_next).hide();

jQuery(imageid).hide();
jQuery(frame).fadeIn('slow');

var framecode="<iframe id='youtube_url' src='http://www.youtube.com/embed/"+frame_id+"?autoplay=1&controls=0&wmode=opaque&cc_load_policy=1&rel=0&iv_load_policy=3&loop=0' width='"+img_yt_thumbnail_w+"' height='"+img_yt_thumbnail_h+"'></iframe>";

jQuery(frame_id_yt).html(framecode);

jQuery(palybuttn).hide();
jQuery(pausebuttn).show();
jQuery(vedio_stat_field).val('play');
}
else if(func=="stopVideo")
{
var img_stop = '.img_stop_'+u_id;
var img_play = '.img_play_'+u_id;

jQuery(img_stop).show();
jQuery(img_play).show();

var img_prev = '.img_prev_'+u_id;
var img_next = '.img_next_'+u_id;

jQuery(img_prev).show();
jQuery(img_next).show();

jQuery(frame).hide();

var framecode="";
jQuery(frame_id_yt).html(framecode);

jQuery(imageid).fadeIn('slow');
jQuery(palybuttn).show();
jQuery(pausebuttn).hide();
jQuery(vedio_stat_field).val('stop');
}
if(!frame_id) return;
if(frame_id_dpl.id) frame_id_dpl = frame_id_dpl.id;
else if(typeof jQuery != "undefined" && frame_id_dpl instanceof jQuery && frame_id_dpl.length) frame_id = frame_id_dpl.get(0).id;
if(!document.getElementById(frame_id_dpl)) return;
args = args || [];
/*Searches the document for the IFRAME with id=frame_id*/
var all_iframes = document.getElementsByTagName("iframe");
for(var i=0, len=all_iframes.length; i<len; i++){
if(all_iframes[i].id == frame_id_dpl || all_iframes[i].parentNode.id == frame_id){
/*The index of the IFRAME element equals the index of the iframe in
the frames object (<frame> . */
window.frames[i].postMessage(JSON.stringify({
"event": "command",
"func": func,
"args": args,
"id": frame_id
}), "*");
}
}
}
function acx_play_vimeo_video(vedio_id,id,u_id)
{
var img_vm_thumbnail_element = ".acx_ssm_vm_"+u_id+"_"+id;
var img_vm_thumbnail_h = jQuery(img_vm_thumbnail_element).height();  
var img_vm_thumbnail_w = jQuery(img_vm_thumbnail_element).width(); 

var iframe_id = "#player_"+vedio_id+u_id;
var iframe = jQuery(iframe_id)[0],
player = iframe;
var frame ='#'+vedio_id+u_id+id;
var frame_id_vimeo ='#'+vedio_id+u_id+"_frame_"+id;
var imageid = '#acx_image_vimeo_'+u_id+'_'+id;
var vedio_stat_field ='#acx_hidden_id_'+u_id;
var palybuttn = '.acx_dis_vplay_but_'+u_id;
var pausebuttn = '.acx_dis_vpause_but_'+u_id;

var img_stop = '.img_stop_'+u_id;
var img_play = '.img_play_'+u_id;

jQuery(img_stop).hide();
jQuery(img_play).hide();

var img_prev = '.img_prev_'+u_id;
var img_next = '.img_next_'+u_id;

jQuery(img_prev).hide();
jQuery(img_next).hide();

jQuery(vedio_stat_field).val('play');
jQuery(imageid).hide();
jQuery(frame).fadeIn('slow');

var framecode="<iframe src='https://player.vimeo.com/video/"+vedio_id+"?player_id=player&autoplay=1&title=0&byline=0&portrait=0&loop=0' width='"+img_vm_thumbnail_w+"' height='"+img_vm_thumbnail_h+"'></iframe>";

jQuery(frame_id_vimeo).html(framecode);

jQuery(palybuttn).hide();
jQuery(pausebuttn).show();
}
function acx_stop_vimeo_video(vedio_id,id,u_id)
{
var iframe_id = "#player_"+vedio_id+u_id;
var iframe = jQuery(iframe_id)[0],
player = iframe;
var frame_id_vimeo ='#'+vedio_id+u_id+"_frame_"+id;
var frame ='#'+vedio_id+u_id+id;
var imageid = '#acx_image_vimeo_'+u_id+'_'+id;
var vedio_stat_field ='#acx_hidden_id_'+u_id;
var palybuttn = '.acx_dis_vplay_but_'+u_id;
var pausebuttn = '.acx_dis_vpause_but_'+u_id;
var framecode="";
jQuery(frame_id_vimeo).html(framecode);

var img_stop = '.img_stop_'+u_id;
var img_play = '.img_play_'+u_id;

jQuery(img_stop).show();
jQuery(img_play).show();

var img_prev = '.img_prev_'+u_id;
var img_next = '.img_next_'+u_id;

jQuery(img_prev).show();
jQuery(img_next).show();

jQuery(frame).hide();
jQuery(imageid).fadeIn('slow');
jQuery(palybuttn).show();
jQuery(pausebuttn).hide();
jQuery(vedio_stat_field).val('stop');
}
</script>
<?php
}
add_action('wp_head','acx_slideshow_api_js');

// rename the gallery with new value
$uid =0;
if(!isset($acx_slideshow_js_array))
{
	$acx_slideshow_js_array = array();
} 
function acx_slideshow($gallery_name="",$gallery_height="",$gallery_width="")
{
	global $acx_slideshow_imageupload_complete_data,$acx_slideshow_gallery_data,$uid,$acx_slideshow_js_array;
	if($acx_slideshow_js_array=="")
{
	$acx_slideshow_js_array = array();
}
	if($gallery_height == "")
	{
		if(is_array($acx_slideshow_gallery_data) && array_key_exists($gallery_name,$acx_slideshow_gallery_data))
		{
			if(array_key_exists('acx_slideshow_height',$acx_slideshow_gallery_data[$gallery_name]))
			{
				$acx_slideshow_height = $acx_slideshow_gallery_data[$gallery_name]['acx_slideshow_height'];	
			}
			else
			{
				$acx_slideshow_height ='';
			}
		} 
		else
		{
			$acx_slideshow_height = '';
		}
	}
	else
	{
		$acx_slideshow_height = $gallery_height;
	}
	
	if($gallery_width == "")
	{
		if(is_array($acx_slideshow_gallery_data) && array_key_exists($gallery_name,$acx_slideshow_gallery_data))
		{
			if(array_key_exists('acx_slideshow_width',$acx_slideshow_gallery_data[$gallery_name]))
			{
				$acx_slideshow_width =  $acx_slideshow_gallery_data[$gallery_name]['acx_slideshow_width'];
			}
			else
			{
				$acx_slideshow_width ='';
			}
		}  
		else
		{
			$acx_slideshow_width = '';
		}
	}
	else
	{
		$acx_slideshow_width = $gallery_width;
	}
	if(array_key_exists($gallery_name,$acx_slideshow_imageupload_complete_data))
	{
		$slide_count=count($acx_slideshow_imageupload_complete_data[$gallery_name]);
	}
	else
	{
		$slide_count=0;
	}
	$gal_found = 0;
	foreach($acx_slideshow_imageupload_complete_data as $key => $value)
	{
		if($key==$gallery_name)
		{
			$gal_found = 1;
		}
	}
		
	$acx_gallery_uid = str_replace(' ','_',$gallery_name);
	if($gal_found==0)
	{
		?>
		<div class="acx_ss_error">
		The gallery group <b><?php echo $gallery_name; ?></b> is no longer available , Please reconfigure your widget/shortcode/phpcode which displays this slideshow.
		</div>
		<?php
	}
	elseif($slide_count == 0)
	{
		?>
		<div class="acx_ss_error">
		The gallery group <b><?php echo $gallery_name; ?></b> is no data available , Please add images or videos to <b><?php echo $gallery_name; ?></b>.
		</div>
		<?php
	} 
	elseif($acx_slideshow_height=="" || $acx_slideshow_width=="" || $acx_slideshow_height=="0px" || $acx_slideshow_width=="0px")
	{
		?>
		<div class="acx_ss_error">
		You have not configured height or width for the slideshow group <b><?php echo $gallery_name; ?></b>, Please go to wp-admin -> Slideshow -> Manage Gallery -> Advanced Settings and configure Slideshow width and height. 
		</div>
		<?php
	}
	else
	{
	?>
	
<!--   Finnal Output  -->
<div id="image_slideshow_holder" style='<?php if($acx_slideshow_width!="") { ?>max-width:<?php echo $acx_slideshow_width; ?>; <?php }?>'>

	<ul id='image_slideshow' class="acx_ppt acx_ppt_<?php echo $uid; ?> acx_ppt_uid_<?php echo$acx_gallery_uid; ?>" style="<?php if($acx_slideshow_width!="") { ?>width:<?php echo $acx_slideshow_width; ?>; <?php } if($acx_slideshow_height!=""&&$acx_slideshow_width!=""){ echo "overflow:hidden;"; }  ?>">
	<?php 
	for($i = 0 ; $i<$slide_count; $i++)
	{
		if($acx_slideshow_imageupload_complete_data[$gallery_name][$i]['type']=="image") 
		{
			$acx_slideshow_link_url = $acx_slideshow_imageupload_complete_data[$gallery_name][$i]['link_url'];
			$acx_slideshow_target = $acx_slideshow_imageupload_complete_data[$gallery_name][$i]['link_target']; 
			if($acx_slideshow_target=="")
			{
				$acx_slideshow_target = "_blank";
			}
			?>
			<li>
			
			<?php
			if($acx_slideshow_link_url!="")
			{
				?>
				<a href="<?php echo $acx_slideshow_link_url; ?>" target="<?php echo $acx_slideshow_target; ?>" title="<?php echo  $acx_slideshow_imageupload_complete_data[$gallery_name][$i]['image_title']; ?>">
				<?php
			}
			?>
			<img src="<?php echo $acx_slideshow_imageupload_complete_data[$gallery_name][$i]['image_url']; ?>" title="<?php echo  $acx_slideshow_imageupload_complete_data[$gallery_name][$i]['image_title']; ?>" alt="<?php $acx_slideshow_imageupload_complete_data[$gallery_name][$i]['image_alttext']; ?>" style="<?php if ($acx_slideshow_height != ""){ ?>max-height:<?php echo $acx_slideshow_height;?>; <?php } ?><?php if ($acx_slideshow_width != ""){ ?> width:<?php echo $acx_slideshow_width;?>; <?php } ?>">
			<?php
			if($acx_slideshow_link_url!="")
			{
				?>
				</a>
				<?php
			}
			?>
			<div class='img_prev_<?php echo $uid; ?> img_prev'></div>
			<div class='img_next_<?php echo $uid; ?> img_next'></div>

			</li>
			<?php
		}
		else if($acx_slideshow_imageupload_complete_data[$gallery_name][$i]['type']=="youtube_video")
		{	
			$vedio_id = "http://www.youtube.com/embed/".$acx_slideshow_imageupload_complete_data[$gallery_name][$i]['vedio_id']."?enablejsapi=1";
			?>
			<li>
			
			<div style="display:none;<?php if ($acx_slideshow_height != ""){ ?>max-height:<?php echo $acx_slideshow_height;?>;<?php } ?>" class="acx_v_hold" id="<?php echo $acx_slideshow_imageupload_complete_data[$gallery_name][$i]['vedio_id'].$uid.$i; ?>">
			
			<div id='<?php echo $acx_slideshow_imageupload_complete_data[$gallery_name][$i]['vedio_id'].$uid; ?>_frame_<?php echo $i; ?>' style='width:100%;height:100%;'></div>
			
			<div class="acx_no_mo" style="height:100%;width:100%;"></div>
			</div>
			
			<img class="acx_ssm_yt_<?php echo $uid; ?>_<?php echo $i; ?>" id="acx_image_<?php echo $uid; ?>_<?php echo $i; ?>" src="<?php echo $acx_slideshow_imageupload_complete_data[$gallery_name][$i]['thumbnail_image']; ?>" style="<?php if ($acx_slideshow_height != ""){ ?>max-height:<?php echo $acx_slideshow_height;?>; <?php } ?><?php if ($acx_slideshow_width != ""){ ?> width:<?php echo $acx_slideshow_width;?>; <?php } ?>" />
			
			<div class="acx_dis_play_but acx_dis_yplay_but_<?php echo $uid; ?>" style="cursor:pointer;" title="" onclick ="call_acx_y_player('<?php echo $acx_slideshow_imageupload_complete_data[$gallery_name][$i]['vedio_id']; ?>','<?php echo"playVideo"; ?>',<?php echo $i; ?>,<?php echo $uid; ?>);"> </div>
			<div class="player_cntrls">
			
			<div class="acx_dis_pause_but acx_dis_ypause_but_<?php echo $uid; ?>" style="cursor:pointer;" title="" onclick ="call_acx_y_player('<?php echo $acx_slideshow_imageupload_complete_data[$gallery_name][$i]['vedio_id']; ?>','<?php echo"stopVideo"; ?>',<?php echo $i; ?>,<?php echo $uid; ?>);"> </div>
			</div>
			
			<div class='img_prev_<?php echo $uid; ?> img_prev'></div>
			<div class='img_next_<?php echo $uid; ?> img_next'></div>

			</li>
			<?php	
		}
		else if($acx_slideshow_imageupload_complete_data[$gallery_name][$i]['type']=="vimeo_video")
		{
			$vedio_id = "http://player.vimeo.com/video/".$acx_slideshow_imageupload_complete_data[$gallery_name][$i]['vedio_id'];
			?>
			<li>
			
			<div style="display:none;<?php if ($acx_slideshow_height != ""){ ?>max-height:<?php echo $acx_slideshow_height;?>;<?php }  ?>" class="acx_v_hold" id="<?php echo $acx_slideshow_imageupload_complete_data[$gallery_name][$i]['vedio_id'].$uid.$i; ?>">
			
			<div id='<?php echo $acx_slideshow_imageupload_complete_data[$gallery_name][$i]['vedio_id'].$uid; ?>_frame_<?php echo $i; ?>' style='width:100%;height:100%;'></div>
			
			<div class="acx_no_mo" style="height:100%;width:100%;"></div>
			</div>
			<img class="acx_ssm_vm_<?php echo $uid; ?>_<?php echo $i; ?>" id="acx_image_vimeo_<?php echo $uid; ?>_<?php echo $i; ?>" src="<?php echo $acx_slideshow_imageupload_complete_data[$gallery_name][$i]['thumbnail_image']; ?>" style="<?php if ($acx_slideshow_height != ""){ ?>max-height:<?php echo $acx_slideshow_height;?>; <?php } ?><?php if ($acx_slideshow_width != ""){ ?> width:<?php echo $acx_slideshow_width;?>; <?php } ?>"/>
			
            <div class="acx_dis_play_but acx_dis_vplay_but_<?php echo $uid; ?>" style="cursor:pointer;" title="" onclick ="acx_play_vimeo_video('<?php echo $acx_slideshow_imageupload_complete_data[$gallery_name][$i]['vedio_id']; ?>',<?php echo $i; ?>,<?php echo $uid; ?>);"> </div>
			<div class="player_cntrls">
			
			<div class="acx_dis_pause_but acx_dis_vpause_but_<?php echo $uid; ?>" style="cursor:pointer;" title="" onclick ="acx_stop_vimeo_video('<?php echo $acx_slideshow_imageupload_complete_data[$gallery_name][$i]['vedio_id']; ?>',<?php echo $i; ?>,<?php echo $uid; ?>);"> </div>
			</div>
			
			<div class='img_prev_<?php echo $uid; ?> img_prev'></div>
			<div class='img_next_<?php echo $uid; ?> img_next'></div>

			</li>
			<?php
		}
	}
	
	?>
	<input type="hidden" id="acx_hidden_id_<?php echo $uid; ?>" value="stop"/>
	</ul>
</div><!-- image_slideshow_holder -->
	<?php
	global $acx_slideshow_gallery_data;
	if(is_array($acx_slideshow_gallery_data) && array_key_exists($gallery_name,$acx_slideshow_gallery_data))
	{
		if(array_key_exists('acx_slideshow_timespan',$acx_slideshow_gallery_data[$gallery_name]) && array_key_exists('acx_slideshow_fadeouttime',$acx_slideshow_gallery_data[$gallery_name]) && array_key_exists('acx_slideshow_fadeintime',$acx_slideshow_gallery_data[$gallery_name]) && array_key_exists('acx_slideshow_pauseon_hover',$acx_slideshow_gallery_data[$gallery_name]))
		{
			$acx_slideshow_timespan = $acx_slideshow_gallery_data[$gallery_name]['acx_slideshow_timespan'];
			$acx_slideshow_fadeouttime = $acx_slideshow_gallery_data[$gallery_name]['acx_slideshow_fadeouttime'];
			$acx_slideshow_fadeintime = $acx_slideshow_gallery_data[$gallery_name]['acx_slideshow_fadeintime'];
			$acx_slideshow_pauseon_hover = $acx_slideshow_gallery_data[$gallery_name]['acx_slideshow_pauseon_hover'];
		}
		else
		{
			$acx_slideshow_timespan="";
			$acx_slideshow_fadeintime="";
			$acx_slideshow_fadeouttime ="";
			$acx_slideshow_pauseon_hover ="";
		}
		if($acx_slideshow_timespan==""||$acx_slideshow_timespan==0)
		{
			$acx_slideshow_timespan = 4000;
		}
		else
		{
			$acx_slideshow_timespan = $acx_slideshow_timespan * 1000;
		}
		if($acx_slideshow_fadeintime=="" || $acx_slideshow_fadeintime ==0)
		{
			$acx_slideshow_fadeintime = 1000;
		}
		else
		{
			$acx_slideshow_fadeintime = $acx_slideshow_fadeintime * 1000;
		}
		if($acx_slideshow_fadeouttime == "" || $acx_slideshow_fadeouttime == 0)
		{
			$acx_slideshow_fadeouttime = 1000;
		}
		else
		{
			$acx_slideshow_fadeouttime = $acx_slideshow_fadeouttime * 1000;
		}
	}
	else
	{
		$acx_slideshow_timespan = '4000';
		$acx_slideshow_fadeintime = '1000';
		$acx_slideshow_fadeouttime = '1000';
		$acx_slideshow_pauseon_hover ="true";
	}
	$acx_slideshow_js_array[$uid]= (array(
										"acx_slideshow_timespan"=>$acx_slideshow_timespan,
										"acx_slideshow_fadeouttime"=>$acx_slideshow_fadeouttime,
										"acx_slideshow_fadeintime"=>$acx_slideshow_fadeintime,
										"acx_slideshow_pauseon_hover"=>$acx_slideshow_pauseon_hover
									));
	}
	$uid = $uid+1;	
}
function acx_slideshow_js_show()
{
global $acx_slideshow_js_array;	
?>
<!-- Starting of Javascript Generated by Simple Slideshow Manager -->
<script type="text/javascript">
<?php
foreach($acx_slideshow_js_array as $key => $value)
{
 $acx_slideshow_timespan = $acx_slideshow_js_array[$key]['acx_slideshow_timespan'];
 $acx_slideshow_fadeouttime = $acx_slideshow_js_array[$key]['acx_slideshow_fadeouttime'];
 $acx_slideshow_fadeintime = $acx_slideshow_js_array[$key]['acx_slideshow_fadeintime'];
 $acx_slideshow_pauseon_hover = $acx_slideshow_js_array[$key]['acx_slideshow_pauseon_hover'];
 $uid = $key;
?>
jQuery('.acx_ppt_<?php echo $uid; ?> li:gt(0)').addClass('inactive');
jQuery('.acx_ppt_<?php echo $uid; ?> li:last').addClass('last');
jQuery('.acx_ppt_<?php echo $uid; ?> li:last').addClass('inactive');
jQuery('.acx_ppt_<?php echo $uid; ?> li:first').addClass('first');
jQuery('.acx_ppt_<?php echo $uid; ?> li:first').addClass('active');

jQuery('#img_play_<?php echo $uid; ?>').addClass('inactive');
var sliderHover_<?php echo $uid; ?> = false;

var cur_<?php echo $uid; ?> = jQuery('.acx_ppt_<?php echo $uid; ?> li:first');
var interval;

jQuery('.img_next_<?php echo $uid; ?>').click( function() 
{
	goFwd_<?php echo $uid; ?>();
	showPause_<?php echo $uid; ?>();
} );

jQuery('.img_prev_<?php echo $uid; ?>').click( function() 
{
	goBack_<?php echo $uid; ?>();
	showPause_<?php echo $uid; ?>();
	
} );

jQuery('#img_stop_<?php echo $uid; ?>').click( function()
 {

	stop_<?php echo $uid; ?>();
	showPlay_<?php echo $uid; ?>();
} );

jQuery('#img_play_<?php echo $uid; ?>').click( function() 
{
	start_<?php echo $uid; ?>();
	showPause_<?php echo $uid; ?>();
} );

function goFwd_<?php echo $uid; ?>() 
{
	stop_<?php echo $uid; ?>();
	forward_<?php echo $uid; ?>();
	start_<?php echo $uid; ?>();
}

function goBack_<?php echo $uid; ?>() 
{
	stop_<?php echo $uid; ?>();
	back_<?php echo $uid; ?>();
	start_<?php echo $uid; ?>();
}

function forward_<?php echo $uid; ?>() 
{

var hidden_id = 'acx_hidden_id_'+<?php echo $uid; ?>;
var vedio_status = document.getElementById(hidden_id).value;
if(vedio_status!="play")
{
	cur_<?php echo $uid; ?>.animate({
										opacity: 0
									}, <?php echo $acx_slideshow_fadeouttime ?>).addClass('inactive').removeClass('active');
	
	if ( cur_<?php echo $uid; ?>.hasClass('last'))
	cur_<?php echo $uid; ?> = jQuery('.acx_ppt_<?php echo $uid; ?> li:first');
	else
	cur_<?php echo $uid; ?> = cur_<?php echo $uid; ?>.next();
	cur_<?php echo $uid; ?>.animate({
										opacity: 1
									}, <?php echo $acx_slideshow_fadeintime ?>).addClass('active').removeClass('inactive');
}
}
jQuery(".acx_ppt_<?php echo $uid; ?>").hover(
function () 
{
<?php 
if($acx_slideshow_pauseon_hover=="true")
{ 
?>
stop_<?php echo $uid; ?>();
showPlay_<?php echo $uid; ?>();
var hidden_id = 'acx_hidden_id_'+<?php echo $uid; ?>;
var vedio_status = document.getElementById(hidden_id).value;
if(vedio_status=="play")
{
jQuery('#img_play_<?php echo $uid; ?>').hide();
jQuery('#img_stop_<?php echo $uid; ?>').hide();
}
<?php } ?>
},
function ()
{
<?php 
if($acx_slideshow_pauseon_hover=="true")
{ 
?>
var hidden_id = 'acx_hidden_id_'+<?php echo $uid; ?>;
var vedio_status = document.getElementById(hidden_id).value;
if(vedio_status!="play")
{
stop_<?php echo $uid; ?>();
start_<?php echo $uid; ?>();
showPause_<?php echo $uid; ?>();

}
<?php } ?>
}
);

function back_<?php echo $uid; ?>() 
{
var hidden_id = 'acx_hidden_id_'+<?php echo $uid; ?>;
var vedio_status = document.getElementById(hidden_id).value;
if(vedio_status!="play")
{
	cur_<?php echo $uid; ?>.animate({
										opacity: 0
									}, <?php echo $acx_slideshow_fadeouttime ?>).addClass('inactive').removeClass('active');
	if ( cur_<?php echo $uid; ?>.hasClass('first'))
	cur_<?php echo $uid; ?> = jQuery('.acx_ppt_<?php echo $uid; ?> li:last');
	else
	cur_<?php echo $uid; ?> = cur_<?php echo $uid; ?>.prev();
	cur_<?php echo $uid; ?>.animate({
										opacity: 1
									}, <?php echo $acx_slideshow_fadeintime ?>).addClass('active').removeClass('inactive');
}
}

function showPause_<?php echo $uid; ?>() 
{
	jQuery('#img_play_<?php echo $uid; ?>').hide();
	jQuery('#img_stop_<?php echo $uid; ?>').show();
}

function showPlay_<?php echo $uid; ?>() 
{
	jQuery('#img_stop_<?php echo $uid; ?>').hide();
	jQuery('#img_play_<?php echo $uid; ?>').show();
}

function start_<?php echo $uid; ?>() 
{
	interval_<?php echo $uid; ?> = setInterval( "forward_<?php echo $uid; ?>()", <?php echo $acx_slideshow_timespan  ?> );
}

function stop_<?php echo $uid; ?>()
{
	clearInterval( interval_<?php echo $uid; ?> );
}

jQuery(function() 
{
	start_<?php echo $uid; ?>();
} );

jQuery( document ).ready(function() {
acx_hidden_id_<?php echo $uid; ?> = jQuery("#acx_hidden_id_<?php echo $uid; ?>").val('stop');
});
<?php
}
?>
</script>
<!-- Ending of Javascript Generated by Advanced Slideshow Manager -->	

<?php
}
add_action('wp_footer', 'acx_slideshow_js_show');
function acx_slideshow_sc( $atts ) 
{
	extract( shortcode_atts( array(
									'name' => "",
									'height' => "",
									'width' => "",), $atts 
								) );
	$temp_height = str_replace("%","",$height);
	$temp_height = str_replace("px","",$temp_height);
	if(!is_numeric($temp_height))
	{
		$height = "";
	}
	$temp_width = str_replace("%","",$width);
	$temp_width = str_replace("px","",$temp_width);
	if(!is_numeric($temp_width))
	{
		$width = "";
	}
	ob_start();
	acx_slideshow($name,$height,$width);
	$content = ob_get_contents();
	ob_end_clean();
	return $content;
}add_shortcode( 'acx_slideshow', 'acx_slideshow_sc' );
// Starting Widget Code
class Acx_Slideshow_Widget extends WP_Widget
{

// Register the widget
    function Acx_Slideshow_Widget() 
	{
// Set some widget options
        $widget_options = array('description' => __('Allow users to include a slideshow  From Acurax Slideshow Plugin','simple-slideshow-manager'),'classname' => 'acx_slideshow_desc');
		$control_options = array('width' => 300);
		$this->WP_Widget('acx_slideshow_widget','Simple Slideshow Manager', $widget_options ,$control_options );
    }
// Output the content of the widget
    function widget($args, $instance) 
	{
        extract( $args ); // Don't worry about this
// Get our variables
        $title = apply_filters( 'widget_title', $instance['title'] );
		$gallery_name = $instance['gallery_name'];
		$gallery_height = $instance['gallery_height'];
		$gallery_width = $instance['gallery_width'];
// This is defined when you register a sidebar
        echo $before_widget;
        // If our title isn't empty then show it
        if ( $title ) 
		{
            echo $before_title . $title . $after_title;
        }
		acx_slideshow($gallery_name,$gallery_height,$gallery_width);
// This is defined when you register a sidebar
        echo $after_widget;
    }
// Output the admin options form
	function form($instance) 
	{
// These are our default values
		$defaults = array( 'title' => __('Simple Slideshow','simple-slideshow-manager'),'gallery_name' => '','gallery_height' => '150px','gallery_width' => '200px' );
// This overwrites any default values with saved values
		$instance = wp_parse_args( (array) $instance, $defaults );
		?>
			<p>
				<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
				<input id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" 
				value="<?php echo $instance['title']; ?>" type="text" class="widefat" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('gallery_name'); ?>"><?php _e('Gallery Name:'); ?></label>
				<select class="widefat" name="<?php echo $this->get_field_name('gallery_name'); ?>" id="<?php echo $this
				->get_field_id('gallery_name'); ?>">
				<?php
				$acx_slideshow_gallery_data = unserialize(get_option('acx_slideshow_gallery_data'));
				$acx_slideshow_imageupload_complete_data=unserialize(get_option('acx_slideshow_imageupload_complete_data'));
				foreach ($acx_slideshow_imageupload_complete_data as $key => $value)
			    {
				?>
				<option value="<?php echo $key; ?>"<?php if ($instance['gallery_name'] == $key) { echo 'selected="selected"'; } ?>><?php echo $key; ?> </option>
				<?php
				}
				?>
				</select>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('gallery_width'); ?>"><?php _e('Slide Width:'); ?></label>
				<input id="<?php echo $this->get_field_id('gallery_width'); ?>" name="<?php echo $this->get_field_name('gallery_width'); ?>"
				value="<?php echo $instance['gallery_width']; ?>" type="text" class="widefat" />
				Width is Needed Eg:200px
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('gallery_height'); ?>"><?php _e('Slide Height:'); ?></label>
				<input id="<?php echo $this->get_field_id('gallery_height'); ?>" name="<?php echo $this->get_field_name('gallery_height'); ?>"
				value="<?php echo $instance['gallery_height']; ?>" type="text" class="widefat" />
				Height is Needed Eg:150px
			</p>
			
		<?php
	}
// Processes the admin options form when saved
	function update($new_instance, $old_instance) 
	{
// Get the old values
		$instance = $old_instance;
// Update with any new values (and sanitise input)
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['gallery_name'] = strip_tags( $new_instance['gallery_name'] );
		$instance['gallery_height'] = strip_tags( $new_instance['gallery_height'] );
		$instance['gallery_width'] = strip_tags( $new_instance['gallery_width'] );
		return $instance;
	}
} add_action('widgets_init', create_function('', 'return register_widget("Acx_Slideshow_Widget");'));
// Ending Widget Codes

// Starting Ajax
function acx_slideshow_ajax_upload_callback()
{
	global $wpdb,$acx_slideshow_misc_user_level;
	
	if(ISSET($_POST['image_url']))
	{
		$image_url = $_POST['image_url'];
	}
	else
	{
		$image_url = '';
	}
	
	if(ISSET($_POST['gallery_name']))
	{
		$gallery_name = $_POST['gallery_name'];
	}
	else
	{
		$gallery_name = '';
	}
	
	if(ISSET($_POST['image_title']))
	{
		$image_title = $_POST['image_title'];
	}
	else
	{
		$image_title = '';
	}
	
	if(ISSET($_POST['image_caption']))
	{
		$image_caption = $_POST['image_caption'];
	}
	else
	{
		$image_caption = '';
	}
	
	if(ISSET($_POST['image_alttext']))
	{
		$image_alttext = $_POST['image_alttext'];
	}
	else
	{
		$image_alttext = '';
	}
	
	if(ISSET($_POST['image_desc']))
	{
		$image_desc = $_POST['image_desc'];
	}
	else
	{
		$image_desc = '';
	}
	
	if(ISSET($_POST['video_url']))
	{
		$video_url = $_POST['video_url'];
	}
	else
	{
		$video_url = '';
	}

	if(ISSET($_POST['type']))
	{
		$type = $_POST['type'];
	}
	else
	{
		$type = '';
	}
	
	$acx_slideshow_imageupload_complete_data = unserialize(get_option('acx_slideshow_imageupload_complete_data'));
	$slide_count=count($acx_slideshow_imageupload_complete_data[$gallery_name]);
	
/* insert image info to array */
	if($type=="image")
	{
		$acx_slideshow_imageupload_complete_data[$gallery_name][$slide_count]=(array(
																		"type"=>$type,
																		"image_url" =>$image_url,
																		"image_title"=>$image_title,
																		"image_caption" =>$image_caption,
																		"image_alttext" =>$image_alttext,
																		"image_desc" =>$image_desc,
																		"link_url"=>"",
																		"link_target"=>""
																	  ));
	}
	else if($type=="youtube_video")
	{
		$regexstr = '~
            # Match Youtube link and embed code
            (?:                             # Group to match embed codes
                (?:<iframe [^>]*src=")?       # If iframe match up to first quote of src
                |(?:                        # Group to match if older embed
                    (?:<object .*>)?      # Match opening Object tag
                    (?:<param .*</param>)*  # Match all param tags
                    (?:<embed [^>]*src=")?  # Match embed tag to the first quote of src
                )?                          # End older embed code group
            )?                              # End embed code groups
            (?:                             # Group youtube url
                https?:\/\/                 # Either http or https
                (?:[\w]+\.)*                # Optional subdomains
                (?:                         # Group host alternatives.
                youtu\.be/                  # Either youtu.be,
                | youtube\.com              # or youtube.com
                | youtube-nocookie\.com     # or youtube-nocookie.com
                )                           # End Host Group
                (?:\S*[^\w\-\s])?           # Extra stuff up to VIDEO_ID
                ([\w\-]{11})                # $1: VIDEO_ID is numeric
                [^\s]*                      # Not a space
            )                               # End group
            "?                              # Match end quote if part of src
            (?:[^>]*>)?                       # Match any extra stuff up to close brace
            (?:                             # Group to match last embed code
                </iframe>                 # Match the end of the iframe
                |</embed></object>          # or Match the end of the older embed
            )?                              # End Group of last bit of embed code
            ~ix';
 
        preg_match($regexstr, $video_url, $matches);
		$imgid = $matches[1];
		$thumbnail_image = "http://img.youtube.com/vi/".$imgid."/hqdefault.jpg";
		$acx_slideshow_imageupload_complete_data[$gallery_name][$slide_count]=(array(
																		"type"=>$type,
																		"video_url" =>$video_url,
																		"thumbnail_image"=>$thumbnail_image,
																		"vedio_id" =>$imgid
																		
																	  ));
	}
	else if($type=="vimeo_video")
	{	


		$regexstr = '~
				# Match Vimeo link and embed code
				(?:<iframe [^>]*src=")?       # If iframe match up to first quote of src
				(?:                         # Group vimeo url
				https?:\/\/             # Either http or https
				(?:[\w]+\.)*            # Optional subdomains
				vimeo\.com              # Match vimeo.com
				(?:[\/\w]*\/videos?)?   # Optional video sub directory this handles groups links also
				\/                      # Slash before Id
				([0-9]+)                # $1: VIDEO_ID is numeric
				[^\s]*                  # Not a space
				)                           # End group
				"?                          # Match end quote if part of src
				(?:[^>]*></iframe>)?        # Match the end of the iframe
				(?:<p>.*</p>)?              # Match any title information stuff
				~ix';
				preg_match($regexstr, $video_url, $matches); 
				$imgid = $matches[1];
				$hash = unserialize(@file_get_contents("http://vimeo.com/api/v2/video/".$imgid.".php"));
				if($hash==false)
				{?>
					<script>
					alert('invalid vimeo url');
					location.reload();
					</script>
				<?php die();
				}
				$thumbnail_image= $hash[0]['thumbnail_medium']; 
		$acx_slideshow_imageupload_complete_data[$gallery_name][$slide_count]=(array(
																		"type"=>$type,
																		"video_url" =>$video_url,
																		"thumbnail_image"=>$thumbnail_image,
																		"vedio_id"=>$imgid
																	  ));
	}
	if (current_user_can($acx_slideshow_misc_user_level)) 
	{
		update_option('acx_slideshow_imageupload_complete_data',serialize($acx_slideshow_imageupload_complete_data));
	}
	
	$slide_count=count($acx_slideshow_imageupload_complete_data[$gallery_name]);
	
/* response display */
	echo "<div id='s_s_notice'>". __('Slides Inserted','simple-slideshow-manager') ."</div>";
	echo"<ul id = \"acx_slideshow_sortable\">";
	for($i = 0  ; $i <$slide_count ; $i++)
	{
		
		if($acx_slideshow_imageupload_complete_data[$gallery_name][$i]['type']=="image")
		{
			echo "<li class=\"ui-state-default\" id = \"recordsArray_".$i."\">";
			echo "<span class=\"ui-icon ui-icon-arrowthick-2-n-s\"></span>";
			echo "<img src = \"".$acx_slideshow_imageupload_complete_data[$gallery_name][$i]['image_url']."\" alt = \"".$acx_slideshow_imageupload_complete_data[$gallery_name][$i]['image_alttext']."\" title = \"".$acx_slideshow_imageupload_complete_data[$gallery_name][$i]['image_title']."\" > &nbsp;";
			echo "<div class=\"del_but\" id=\"acx_delete_image_".$acx_slideshow_imageupload_complete_data[$gallery_name][$i]['image_title']."\" onclick = \"acx_delete(".$i.");\" title=\"Delete This Slide\"></div></br>";
			echo "<div class=\"edit_but\" id=\"acx_edit_image_".$i."\" onclick = \"acx_edit(".$i.");\" title=\"Edit This Slide\"></div></br>";
			echo "<div class=\"add_link\" id=\"acx_edit_image_".$i."\" onclick = \"acx_edit(".$i.");\" title=\"Add Link To This Slide\"></div></br>";	
			echo "</li>";
		}
		else  if($acx_slideshow_imageupload_complete_data[$gallery_name][$i]['type']=="youtube_video")
		{
				echo "<li class=\"ui-state-default\" id = \"recordsArray_".$i."\">";
				echo "<span class=\"ui-icon ui-icon-arrowthick-2-n-s\"></span>";
				echo "<img src=\"".$acx_slideshow_imageupload_complete_data[$gallery_name][$i]['thumbnail_image']."\"  alt = \"".$acx_slideshow_imageupload_complete_data[$gallery_name][$i]['video_url']."\" title = \"".$acx_slideshow_imageupload_complete_data[$gallery_name][$i]['video_url']."\" > &nbsp;";
				echo "<div class=\"play_but\" title=\"\"></div></br>";
				echo "<div class=\"del_but\" id=\"acx_delete_image_".$acx_slideshow_imageupload_complete_data[$gallery_name][$i]['video_url']."\" onclick = \"acx_delete(".$i.");\" title=\"Delete This Slide\"></div></br>";	
				echo "</li>";
		}
		else  if($acx_slideshow_imageupload_complete_data[$gallery_name][$i]['type']=="vimeo_video")
		{
 
					echo "<li class=\"ui-state-default\" id = \"recordsArray_".$i."\">";
				echo "<span class=\"ui-icon ui-icon-arrowthick-2-n-s\"></span>";
				echo "<img src=\"".$acx_slideshow_imageupload_complete_data[$gallery_name][$i]['thumbnail_image']."\"  alt = \"".$acx_slideshow_imageupload_complete_data[$gallery_name][$i]['video_url']."\" title = \"".$acx_slideshow_imageupload_complete_data[$gallery_name][$i]['video_url']."\" > &nbsp;";
				echo "<div class=\"play_but\" title=\"\"></div></br>";
				echo "<div class=\"del_but\" id=\"acx_delete_image_".$acx_slideshow_imageupload_complete_data[$gallery_name][$i]['video_url']."\" onclick = \"acx_delete(".$i.");\" title=\"Delete This Slide\"></div></br>";	
				echo "</li>";
		}
	}
	echo"</ul>";
	die(); // this is required to return a proper result
} add_action('wp_ajax_acx_slideshow_ajax_upload', 'acx_slideshow_ajax_upload_callback');
function acx_slideshow_ajax_updateRecordsListings_callback()
{
	global $wpdb,$acx_slideshow_misc_user_level;
	$social_icon_array_order = $_POST['recordsArray'];
	$gallery_name = $_POST['gallery_name'];
	$acx_slideshow_imageupload_complete_data=unserialize(get_option('acx_slideshow_imageupload_complete_data'));
	$countr = count($social_icon_array_order);
	for($i=0;$i<$countr; $i++)
	{
		$num = $social_icon_array_order[$i];
		$local[$i]=$acx_slideshow_imageupload_complete_data[$gallery_name][$num];
	}	
	$acx_slideshow_imageupload_complete_data[$gallery_name] = $local;
	// ********** response *************
	if (current_user_can($acx_slideshow_misc_user_level)) 
	{
		update_option('acx_slideshow_imageupload_complete_data',serialize($acx_slideshow_imageupload_complete_data));
	}
	$slide_count=count($acx_slideshow_imageupload_complete_data[$gallery_name]);
	echo "<div id='s_s_notice'>".__('Slides Sorted','simple-slideshow-manager') ."</div>";
	echo"<ul id = \"acx_slideshow_sortable\">";
	//echo $slide_count;
	for($i = 0  ; $i <$slide_count ; $i++)
	{
			if($acx_slideshow_imageupload_complete_data[$gallery_name][$i]['type']=="image")
		{
			echo "<li class=\"ui-state-default\" id = \"recordsArray_".$i."\">";
			echo "<span class=\"ui-icon ui-icon-arrowthick-2-n-s\"></span>";
			echo "<img src = \"".$acx_slideshow_imageupload_complete_data[$gallery_name][$i]['image_url']."\" alt = \"".$acx_slideshow_imageupload_complete_data[$gallery_name][$i]['image_alttext']."\" title = \"".$acx_slideshow_imageupload_complete_data[$gallery_name][$i]['image_title']."\" > &nbsp;";
			echo "<div class=\"del_but\" id=\"acx_delete_image_".$acx_slideshow_imageupload_complete_data[$gallery_name][$i]['image_title']."\" onclick = \"acx_delete(".$i.");\" title=\"Delete This Slide\"></div></br>";
			echo "<div class=\"edit_but\" id=\"acx_edit_image_".$i."\" onclick = \"acx_edit(".$i.");\" title=\"Edit This Slide\"></div></br>";
			echo "<div class=\"add_link\" id=\"acx_edit_image_".$i."\" onclick = \"acx_edit(".$i.");\" title=\"Add Link To This Slide\"></div></br>";	
			echo "</li>";
		}
		else  if($acx_slideshow_imageupload_complete_data[$gallery_name][$i]['type']=="youtube_video")
		{
				echo "<li class=\"ui-state-default\" id = \"recordsArray_".$i."\">";
				echo "<span class=\"ui-icon ui-icon-arrowthick-2-n-s\"></span>";
				echo "<img src=\"".$acx_slideshow_imageupload_complete_data[$gallery_name][$i]['thumbnail_image']."\"  alt = \"".$acx_slideshow_imageupload_complete_data[$gallery_name][$i]['video_url']."\" title = \"".$acx_slideshow_imageupload_complete_data[$gallery_name][$i]['video_url']."\" > &nbsp;";
				echo "<div class=\"play_but\" title=\"\"></div></br>";
				echo "<div class=\"del_but\" id=\"acx_delete_image_".$acx_slideshow_imageupload_complete_data[$gallery_name][$i]['video_url']."\" onclick = \"acx_delete(".$i.");\" title=\"Delete This Slide\"></div></br>";	
				echo "</li>";
		}
		else  if($acx_slideshow_imageupload_complete_data[$gallery_name][$i]['type']=="vimeo_video")
		{
			echo "<li class=\"ui-state-default\" id = \"recordsArray_".$i."\">";
				echo "<span class=\"ui-icon ui-icon-arrowthick-2-n-s\"></span>";
				echo "<img src=\"".$acx_slideshow_imageupload_complete_data[$gallery_name][$i]['thumbnail_image']."\"  alt = \"".$acx_slideshow_imageupload_complete_data[$gallery_name][$i]['video_url']."\" title = \"".$acx_slideshow_imageupload_complete_data[$gallery_name][$i]['video_url']."\" > &nbsp;";
				echo "<div class=\"play_but\" title=\"\"></div></br>";
				echo "<div class=\"del_but\" id=\"acx_delete_image_".$acx_slideshow_imageupload_complete_data[$gallery_name][$i]['video_url']."\" onclick = \"acx_delete(".$i.");\" title=\"Delete This Slide\"></div></br>";	
				echo "</li>";
		
		}
	}
	echo"</ul>";
	die(); // this is required to return a proper result
} add_action('wp_ajax_acx_slideshow_ajax_updateRecordsListings', 'acx_slideshow_ajax_updateRecordsListings_callback');
function acx_slideshow_ajax_deleteimage_callback()
{
	global $wpdb,$acx_slideshow_misc_user_level;
	$gallery_name = $_POST['gallery_name'];
	$index = $_POST['index'];
	$acx_slideshow_imageupload_complete_data = unserialize(get_option('acx_slideshow_imageupload_complete_data'));
	$key = array_search($index, $acx_slideshow_imageupload_complete_data[$gallery_name]); 
	unset($acx_slideshow_imageupload_complete_data[$gallery_name][$index]);
	$acx_slideshow_imageupload_complete_data[$gallery_name] = array_values($acx_slideshow_imageupload_complete_data[$gallery_name]);
	if (current_user_can($acx_slideshow_misc_user_level)) 
	{
		update_option('acx_slideshow_imageupload_complete_data',serialize($acx_slideshow_imageupload_complete_data));
	}
	$slide_count=count($acx_slideshow_imageupload_complete_data[$gallery_name]);
	echo "<div id='s_s_notice'>". __('Slides Deleted','simple-slideshow-manager') ."</div>";
	echo"<ul id = \"acx_slideshow_sortable\">";
/* response display */ 

	for($i = 0  ; $i <$slide_count ; $i++)
	{
			if($acx_slideshow_imageupload_complete_data[$gallery_name][$i]['type']=="image")
		{
			echo "<li class=\"ui-state-default\" id = \"recordsArray_".$i."\">";
			echo "<span class=\"ui-icon ui-icon-arrowthick-2-n-s\"></span>";
			echo "<img src = \"".$acx_slideshow_imageupload_complete_data[$gallery_name][$i]['image_url']."\" alt = \"".$acx_slideshow_imageupload_complete_data[$gallery_name][$i]['image_alttext']."\" title = \"".$acx_slideshow_imageupload_complete_data[$gallery_name][$i]['image_title']."\" > &nbsp;";
			echo "<div class=\"del_but\" id=\"acx_delete_image_".$acx_slideshow_imageupload_complete_data[$gallery_name][$i]['image_title']."\" onclick = \"acx_delete(".$i.");\" title=\"Delete This Slide\"></div></br>";
			echo "<div class=\"edit_but\" id=\"acx_edit_image_".$i."\" onclick = \"acx_edit(".$i.");\" title=\"Edit This Slide\"></div></br>";
			echo "<div class=\"add_link\" id=\"acx_edit_image_".$i."\" onclick = \"acx_edit(".$i.");\" title=\"Add Link To This Slide\"></div></br>";
			echo "</li>";
		}
		else  if($acx_slideshow_imageupload_complete_data[$gallery_name][$i]['type']=="youtube_video")
		{
			   echo "<li class=\"ui-state-default\" id = \"recordsArray_".$i."\">";
				echo "<span class=\"ui-icon ui-icon-arrowthick-2-n-s\"></span>";
				echo "<img src=\"".$acx_slideshow_imageupload_complete_data[$gallery_name][$i]['thumbnail_image']."\"  alt = \"".$acx_slideshow_imageupload_complete_data[$gallery_name][$i]['video_url']."\" title = \"".$acx_slideshow_imageupload_complete_data[$gallery_name][$i]['video_url']."\" > &nbsp;";
				echo "<div class=\"play_but\" title=\"\"></div></br>";
				echo "<div class=\"del_but\" id=\"acx_delete_image_".$acx_slideshow_imageupload_complete_data[$gallery_name][$i]['video_url']."\" onclick = \"acx_delete(".$i.");\" title=\"Delete This Slide\"></div></br>";	
				echo "</li>";
		}
		else  if($acx_slideshow_imageupload_complete_data[$gallery_name][$i]['type']=="vimeo_video")
		{
			echo "<li class=\"ui-state-default\" id = \"recordsArray_".$i."\">";
				echo "<span class=\"ui-icon ui-icon-arrowthick-2-n-s\"></span>";
				echo "<img src=\"".$acx_slideshow_imageupload_complete_data[$gallery_name][$i]['thumbnail_image']."\"  alt = \"".$acx_slideshow_imageupload_complete_data[$gallery_name][$i]['video_url']."\" title = \"".$acx_slideshow_imageupload_complete_data[$gallery_name][$i]['video_url']."\" > &nbsp;";
				echo "<div class=\"play_but\" title=\"\"></div></br>";
				echo "<div class=\"del_but\" id=\"acx_delete_image_".$acx_slideshow_imageupload_complete_data[$gallery_name][$i]['video_url']."\" onclick = \"acx_delete(".$i.");\" title=\"Delete This Slide\"></div></br>";	
				echo "</li>";
		}
	}
	echo"</ul>";	
	die(); // this is required to return a proper result
}
 add_action('wp_ajax_acx_slideshow_ajax_deleteimage', 'acx_slideshow_ajax_deleteimage_callback');

function acx_ajax_renamefunction_callback()
{
$status='0';
global $wpdb;
if(ISSET($_POST['newname']))
{
$new_galleryname = trim($_POST['newname']);
}
else
{
$new_galleryname = '';
}
if(ISSET($_POST['newname']))
{
$old_galleryname = trim($_POST['oldname']);
}
else
{
$old_galleryname = '';
}

$acx_slideshow_misc_user_level = get_option('acx_slideshow_misc_user_level');
if($acx_slideshow_misc_user_level=="")
{
$acx_slideshow_misc_user_level = "manage_options";
}
if (current_user_can($acx_slideshow_misc_user_level)) 
{
	$acx_slideshow_imageupload_complete_data=unserialize(get_option('acx_slideshow_imageupload_complete_data'));
	$acx_slideshow_gallery_data = unserialize(get_option('acx_slideshow_gallery_data'));
	$found = '';
	foreach ($acx_slideshow_imageupload_complete_data as $key => $value)
	{
		if($key==$new_galleryname)
		{
			$found="yes";
			$status='1';
		}
	}
	if($found!="yes")
	{
		foreach ($acx_slideshow_imageupload_complete_data as $key => $value)
		{
			
			if($key==$old_galleryname)
			{
				$acx_slideshow_imageupload_complete_data[$new_galleryname] = $acx_slideshow_imageupload_complete_data[$old_galleryname];
				unset($acx_slideshow_imageupload_complete_data[$old_galleryname]);
			}
		}
		update_option('acx_slideshow_imageupload_complete_data',serialize($acx_slideshow_imageupload_complete_data));
		
		foreach ($acx_slideshow_gallery_data as $key => $value)
		{
			if($key==$old_galleryname)
			{
				$acx_slideshow_gallery_data[$new_galleryname] = $acx_slideshow_gallery_data[$old_galleryname];
				unset($acx_slideshow_gallery_data[$old_galleryname]);
			}
		}
		update_option('acx_slideshow_gallery_data',serialize($acx_slideshow_gallery_data));
	$status='2';
	} 
}
	echo $status;
die();

}
add_action('wp_ajax_acx_ajax_renamefunction', 'acx_ajax_renamefunction_callback');

function acx_slideshow_ajax_editimage_callback()
{
	global $wpdb;
	$gallery_name = $_POST['gallery_name'];
	$index = $_POST['index'];
	$acx_slideshow_imageupload_complete_data = unserialize(get_option('acx_slideshow_imageupload_complete_data'));
	$title = $acx_slideshow_imageupload_complete_data[$gallery_name][$index]['image_title'];
	$alttext = $acx_slideshow_imageupload_complete_data[$gallery_name][$index]['image_alttext'];
	$url= $acx_slideshow_imageupload_complete_data[$gallery_name][$index]['link_url'];
	$target =  $acx_slideshow_imageupload_complete_data[$gallery_name][$index]['link_target'];
	echo"<form name=\"acx_editimage_form\" id=\"acx_editimage_form\" method=\"post\" action=\"\">";
	echo"<table cellpadding=\"0\"><tr><td>". __('Image Title','simple-slideshow-manager') ."</td><td><input type=\"text\" id=\"acx_slideshow_edit_title\" name=\"acx_slideshow_edit_title\" size=\"40\" value=\"".$title."\" /></td></tr>";
	echo"<tr><td>". __('Image Alt Text','simple-slideshow-manager') ."</td><td><input type=\"text\" id=\"acx_slideshow_edit_alt\" name=\"acx_slideshow_edit_alt\" size=\"40\" value=\"".$alttext."\" /></td></tr>";
	echo"<tr><td>". __('Link URL','simple-slideshow-manager') ."</td><td><input type=\"text\" id=\"acx_slideshow_edit_url\" name=\"acx_slideshow_edit_url\" size=\"40\" value=\"".$url."\" /></td></tr>"; ?>
	<tr><td><?php _e('Target','simple-slideshow-manager'); ?></td><td><select id="acx_link_target">
	<option value="_blank" <?php if ($target == "_blank") { echo 'selected="selected"'; } ?>><?php _e('_blank','simple-slideshow-manager'); ?></option>
	<option value="_self" <?php if ($target == "_self") { echo 'selected="selected"'; } ?>><?php _e('_self','simple-slideshow-manager'); ?></option>
	<option value="_parent" <?php if ($target == "_parent") { echo 'selected="selected"'; } ?>><?php _e('_parent','simple-slideshow-manager'); ?></option>
	<option value="_top" <?php if ($target == "_top") { echo 'selected="selected"'; } ?>><?php _e('_top','simple-slideshow-manager'); ?></option>
	</select></td></tr>
	<?php
	echo"<tr><td colspan=\"2\"><a href=\"#\" class=\"button can_edit\" id=\"acx_edit_image_link\" onclick=\"acx_slideshow_change_edittext_cancel();\" >". __('Cancel','simple-slideshow-manager') ."</a>";
	echo"<a href=\"#\" class=\"button edit_edit\" id=\"acx_edit_image_link\" onclick=\"acx_slideshow_change_edittext(".$index.");\" >".__('Update','simple-slideshow-manager') ."</a></td></tr></table>";
	echo"</form>";
	die(); // this is required to return a proper result
}
add_action('wp_ajax_acx_slideshow_ajax_editimage','acx_slideshow_ajax_editimage_callback');

function acx_slideshow_ajax_changeedittext_callback()
{
	global $wpdb,$acx_slideshow_misc_user_level;
	$gallery_name = $_POST['gallery_name'];
	$index = $_POST['index'];
	$title = $_POST['title'];
	$alttext = $_POST['alttext'];
	$url = $_POST['url'];
	$target = $_POST['target'];
	$acx_slideshow_imageupload_complete_data = unserialize(get_option('acx_slideshow_imageupload_complete_data'));
	$acx_slideshow_imageupload_complete_data[$gallery_name][$index]['image_title']= $title;
	$acx_slideshow_imageupload_complete_data[$gallery_name][$index]['image_alttext']= $alttext;
	$acx_slideshow_imageupload_complete_data[$gallery_name][$index]['link_url']= $url;
	$acx_slideshow_imageupload_complete_data[$gallery_name][$index]['link_target']= $target;
	if (current_user_can($acx_slideshow_misc_user_level)) 
	{
		update_option('acx_slideshow_imageupload_complete_data',serialize($acx_slideshow_imageupload_complete_data));
	}
	die(); // this is required to return a proper result
	
}
add_action('wp_ajax_acx_slideshow_ajax_changeedittext','acx_slideshow_ajax_changeedittext_callback');

function acx_slideshow_pluign_promotion()
{
	$acx_tweet_text_array = array
						(
						"I am using Simple Slideshow Manager worspress plugin from @acuraxdotcom and you should too",
						"It looks awesome thanks @acuraxdotcom for developing such a wonderful Slideshow plugin",
						"Simple Slideshow Manager from @acuraxdotcom very simple and easy to configure",
						"I have been using Simple Slideshow Manager plugin for a while and it looks nice",
						"Its very nice and flexible Slideshow plugin thanks @acuraxdotcom",
						"Simple Slideshow Managers user interface looks very simple and easy to understand.Good job @acuraxdotcom..", 
						"Simple Slideshow Manager from @acuraxdotcom is too much understandable to beginners.",
						"I installed Simple Slideshow Manager wordpress plugin from @acuraxdotcom and it looks awesome.",
						);
$acx_tweet_text = array_rand($acx_tweet_text_array, 1);
$acx_tweet_text = $acx_tweet_text_array[$acx_tweet_text];

    echo '<div id="acx_td" class="error" style="background: none repeat scroll 0pt 0pt infobackground; border: 1px solid inactivecaption; padding: 5px;line-height:16px;">
	<p>It looks like you have been enjoying using Simple Slideshow Manager plugin from <a href="http://www.acurax.com?utm_source=plugin&utm_medium=thirtyday&utm_campaign=ssm" title="Acurax Web Designing Company" target="_blank">Acurax</a> for atleast 30 days.Would you consider upgrading to <a href="http://www.acurax.com/products/simple-advanced-slideshow-manager/?utm_source=plugin&utm_medium=thirtyday_yellow&utm_campaign=ssm" title="Premium Simple Slideshow Manager" target="_blank">premium version</a> to enjoy more features and help support continued development of the plugin? - Spreading the world about this plugin. Thank you for using the plugin</p>
	<p>
	<a href="http://wordpress.org/support/view/plugin-reviews/simple-slideshow-manager" class="button" style="color:black;text-decoration:none;padding:5px;margin-right:4px;" target="_blank">Rate it 5?\'s on wordpress</a>
	<a href="https://twitter.com/share?url=http://www.acurax.com/products/simple-advanced-slideshow-manager/&text='.$acx_tweet_text.' -" class="button" style="color:black;text-decoration:none;padding:5px;margin-right:4px;" target="_blank">Tell Your Followers</a>
	<a href="http://www.acurax.com/products/simple-advanced-slideshow-manager/?utm_source=plugin&utm_medium=thirtyday&utm_campaign=ssm" class="button" style="color:black;text-decoration:none;padding:5px;margin-right:4px;" target="_blank">Order Premium Version</a>
	<a href="admin.php?page=Acurax-Slideshow-Misc&td=hide" class="button" style="color:black;text-decoration:none;padding:5px;margin-right:4px;margin-left:20px;">Don\'t Show This Again</a>
</p>
		  
		  </div>';
}
$acx_slideshow_installed_date = get_option('acx_slideshow_installed_date');
if ($acx_slideshow_installed_date=="") {
$acx_slideshow_installed_date = time();
update_option('acx_slideshow_installed_date',$acx_slideshow_installed_date);
}
$acx_slideshow_installed_date = get_option('acx_slideshow_installed_date');
if($acx_slideshow_installed_date < ( time() - 2952000 ))
{
	if (get_option('acx_ssm_td') != "hide")
	{
		add_action('admin_notices', 'acx_slideshow_pluign_promotion',1);
	}
}
function acx_slideshow_pluign_finish_version_update()
{
    echo '<div id="message" class="updated">
		  <p><b>Thanks for updating Simple Slideshow Manager plugin... You need to visit <a href="admin.php?page=Acurax-Slideshow-Settings&status=updated#updated">Plugin\'s Settings Page</a> to Complete the Updating Process - <a href="admin.php?page=Acurax-Slideshow-Settings&status=updated#updated">Click Here Visit Simple Slideshow Manager Plugin Settings</a></b></p>
		  </div>';
}
$acx_slideshow_version = get_option('acx_slideshow_version');
if($acx_slideshow_version < "2.1") // << Old Version // Current Verison
{
	add_action('admin_notices', 'acx_slideshow_pluign_finish_version_update',1);
}
function acx_slideshow_pluign_old_version()
{
    echo '<div id="message" class="error">
		  <p><b>You are using an old version of wordpress, You need to have wordpress version 3.5 or above to have the plugin to work properly, please update your wordpress installation.</b></p>
		  </div>';
}
if(!function_exists('wp_enqueue_media'))
{
	add_action('admin_notices', 'acx_slideshow_pluign_old_version',1);
}
function acx_slideshow_comparison($ad=2)
{
$ad_1 = '
</hr>
<a name="compare"></a>
<div id="ssm_lp_compare" style="text-transform: capitalize;">
<a name="compare"></a>
<div class="row_1">
<div class="ssm_lp_compare_row_1_1"></div> <!-- ssm_lp_compare_row_1_1 -->
<div id="ssm_lp_f_group">
<div class="left" style="padding-top: 57px; padding-bottom: 188px;">
Gallery
</div> <!-- left -->
<div class="right">
<div class="ssm_lp_compare_row_1_features">No Limits - Can have any number of galleries</div> <!-- ssm_lp_compare_row_1_features -->
<div class="ssm_lp_compare_row_1_features">Can Customize Each Gallery Transition Effects</div> <!-- ssm_lp_compare_row_1_features -->
<div class="ssm_lp_compare_row_1_features">Can Set Height & Width</div> <!-- ssm_lp_compare_row_1_features -->
<div class="ssm_lp_compare_row_1_features">Enable/Disable Slide Transition Pause on Mouseover</div> <!-- ssm_lp_compare_row_1_features -->
<div class="ssm_lp_compare_row_1_features">Centering Slideshow</div> <!-- ssm_lp_compare_row_1_features -->
<div class="ssm_lp_compare_row_1_features">Random Slideshow</div> <!-- ssm_lp_compare_row_1_features -->
<div class="ssm_lp_compare_row_1_features"> Next and Previous Button Navigation</div> <!-- ssm_lp_compare_row_1_features -->
<div class="ssm_lp_compare_row_1_features">Responsive Slideshow</div> <!-- ssm_lp_compare_row_1_features -->
</div> <!-- right -->
</div> <!-- ssm_lp_f_group -->
<div id="ssm_lp_f_group">
<div class="left" style="padding-bottom: 123px; padding-top: 122px;">
Image Display
</div> <!-- left -->
<div class="right">
<div class="ssm_lp_compare_row_1_features">No Limits - Can Have Any Number of Image Slides</div> <!-- ssm_lp_compare_row_1_features -->
<div class="ssm_lp_compare_row_1_features">Add link to image slides.</div> <!-- ssm_lp_compare_row_1_features -->
<div class="ssm_lp_compare_row_1_features">Add alt text to image slides. (Better for SEO)</div> <!-- ssm_lp_compare_row_1_features -->
<div class="ssm_lp_compare_row_1_features">Show Description on image slides</div> <!-- ssm_lp_compare_row_1_features -->
<div class="ssm_lp_compare_row_1_features">Enable/Disable - Description Always Show</div> <!-- ssm_lp_compare_row_1_features -->
<div class="ssm_lp_compare_row_1_features">Enable/Disable- Show Description Only On Mouseover</div> <!-- ssm_lp_compare_row_1_features -->
<div class="ssm_lp_compare_row_1_features">set image description font color</div> <!-- ssm_lp_compare_row_1_features -->
<div class="ssm_lp_compare_row_1_features">Can set description display position</div> <!-- ssm_lp_compare_row_1_features -->
</div> <!-- right -->
</div> <!-- ssm_lp_f_group -->
<div id="ssm_lp_f_group">
<div class="left" style="padding-bottom: 88px;padding-top: 124px;">
Video Display
</div> <!-- left -->
<div class="right">
<div class="ssm_lp_compare_row_1_features">No Limits - Can Have Any Number of Youtube videos</div> <!-- ssm_lp_compare_row_1_features -->
<div class="ssm_lp_compare_row_1_features">No Limits - Can Have Any Number of Vimeo videos</div> <!-- ssm_lp_compare_row_1_features -->
<div class="ssm_lp_compare_row_1_features">Can choose video control button themes/styles</div> <!-- ssm_lp_compare_row_1_features -->
<div class="ssm_lp_compare_row_1_features">Can upload your own control button icons</div> <!-- ssm_lp_compare_row_1_features -->
<div class="ssm_lp_compare_row_1_features">Can set Video control button background color</div> <!-- ssm_lp_compare_row_1_features -->
<div class="ssm_lp_compare_row_1_features">Mute and unmute control</div> <!-- ssm_lp_compare_row_1_features -->
<div class="ssm_lp_compare_row_1_features">Pause and play control</div> <!-- ssm_lp_compare_row_1_features -->
</div> <!-- right -->
</div> <!-- ssm_lp_f_group -->
<div id="ssm_lp_f_group">
<div class="left" style="padding-top: 437px;padding-bottom: 434px;">
Slide Transition Styles
</div> <!-- left -->
<div class="right">
<div class="ssm_lp_compare_row_1_features">fade</div> <!-- ssm_lp_compare_row_1_features -->
<div class="ssm_lp_compare_row_1_features">blind-X</div> <!-- ssm_lp_compare_row_1_features -->
<div class="ssm_lp_compare_row_1_features">blind-Y</div> <!-- ssm_lp_compare_row_1_features -->
<div class="ssm_lp_compare_row_1_features">blind-Z</div> <!-- ssm_lp_compare_row_1_features -->
<div class="ssm_lp_compare_row_1_features">cover</div> <!-- ssm_lp_compare_row_1_features -->
<div class="ssm_lp_compare_row_1_features">curtain-X</div> <!-- ssm_lp_compare_row_1_features -->
<div class="ssm_lp_compare_row_1_features">curtain-Y</div> <!-- ssm_lp_compare_row_1_features -->
<div class="ssm_lp_compare_row_1_features">fade & Zoom</div> <!-- ssm_lp_compare_row_1_features -->
<div class="ssm_lp_compare_row_1_features">grow-X</div> <!-- ssm_lp_compare_row_1_features -->
<div class="ssm_lp_compare_row_1_features">grow-Y</div> <!-- ssm_lp_compare_row_1_features -->
<div class="ssm_lp_compare_row_1_features">scroll-Up</div> <!-- ssm_lp_compare_row_1_features -->
<div class="ssm_lp_compare_row_1_features">scroll-Down</div> <!-- ssm_lp_compare_row_1_features -->
<div class="ssm_lp_compare_row_1_features">scroll-Left</div> <!-- ssm_lp_compare_row_1_features -->
<div class="ssm_lp_compare_row_1_features">scroll-Right</div> <!-- ssm_lp_compare_row_1_features -->
<div class="ssm_lp_compare_row_1_features">scroll-Horizontal</div> <!-- ssm_lp_compare_row_1_features -->
<div class="ssm_lp_compare_row_1_features">scroll-Vertical</div> <!-- ssm_lp_compare_row_1_features -->
<div class="ssm_lp_compare_row_1_features">shuffle</div> <!-- ssm_lp_compare_row_1_features -->
<div class="ssm_lp_compare_row_1_features">slide-X</div> <!-- ssm_lp_compare_row_1_features -->
<div class="ssm_lp_compare_row_1_features">slide-Y</div> <!-- ssm_lp_compare_row_1_features -->
<div class="ssm_lp_compare_row_1_features">toss</div> <!-- ssm_lp_compare_row_1_features -->
<div class="ssm_lp_compare_row_1_features">turn-Up</div> <!-- ssm_lp_compare_row_1_features -->
<div class="ssm_lp_compare_row_1_features">turn-Down</div> <!-- ssm_lp_compare_row_1_features -->
<div class="ssm_lp_compare_row_1_features">turn-Left</div> <!-- ssm_lp_compare_row_1_features -->
<div class="ssm_lp_compare_row_1_features">turn-Right</div> <!-- ssm_lp_compare_row_1_features -->
<div class="ssm_lp_compare_row_1_features">uncover</div> <!-- ssm_lp_compare_row_1_features -->
<div class="ssm_lp_compare_row_1_features">wipe</div> <!-- ssm_lp_compare_row_1_features -->
<div class="ssm_lp_compare_row_1_features">Zoom</div> <!-- ssm_lp_compare_row_1_features -->
</div> <!-- right -->
</div> <!-- ssm_lp_f_group -->
<div id="ssm_lp_f_group">
<div class="left" style="padding-top: 98px;padding-bottom: 95px;">
Customize Slide Transition 
</div> <!-- left -->
<div class="right">
<div class="ssm_lp_compare_row_1_features">Can set transition InSpeed</div> <!-- ssm_lp_compare_row_1_features -->
<div class="ssm_lp_compare_row_1_features">Can Set transition OutSpeed</div> <!-- ssm_lp_compare_row_1_features -->
<div class="ssm_lp_compare_row_1_features">Can set transition Interval</div> <!-- ssm_lp_compare_row_1_features -->
<div class="ssm_lp_compare_row_1_features">Enable or disable easing</div> <!-- ssm_lp_compare_row_1_features -->
<div class="ssm_lp_compare_row_1_features">Preview Transition While Configuring</div> <!-- ssm_lp_compare_row_1_features -->
<div class="ssm_lp_compare_row_1_features">Enable or disable slide synchronization</div> <!-- ssm_lp_compare_row_1_features -->
<div class="ssm_lp_compare_row_1_features">Can set FadeIn and FadeOut</div> <!-- ssm_lp_compare_row_1_features -->
</div> <!-- right -->
</div> <!-- ssm_lp_f_group -->
<div id="ssm_lp_f_group">
<div class="left" style="padding-top: 57px;padding-bottom: 55px;">
Easy to configure
</div> <!-- left -->
<div class="right">
<div class="ssm_lp_compare_row_1_features">Simple User interface</div> <!-- ssm_lp_compare_row_1_features -->
<div class="ssm_lp_compare_row_1_features">Easy to understand every option settings</div> <!-- ssm_lp_compare_row_1_features -->
<div class="ssm_lp_compare_row_1_features">Drag and Drop to Reorder Slides</div> <!-- ssm_lp_compare_row_1_features -->
<div class="ssm_lp_compare_row_1_features">Set Which User Level Can Manage Slideshow</div> <!-- ssm_lp_compare_row_1_features -->
</div> <!-- right -->
</div> <!-- ssm_lp_f_group -->
<div id="ssm_lp_f_group">
<div class="left" style="padding-top: 123px;padding-bottom: 121px;">
Widget Support
</div> <!-- left -->
<div class="right">
<div class="ssm_lp_compare_row_1_features">Multiple Widget support</div> <!-- ssm_lp_compare_row_1_features -->
<div class="ssm_lp_compare_row_1_features">Simple Widget settings</div> <!-- ssm_lp_compare_row_1_features -->
<div class="ssm_lp_compare_row_1_features">Define Slideshow Size for each widget</div> <!-- ssm_lp_compare_row_1_features -->
<div class="ssm_lp_compare_row_1_features">Define  FadeIn and FadeOut for each widget</div> <!-- ssm_lp_compare_row_1_features -->
<div class="ssm_lp_compare_row_1_features">Define transition Interval for each widget</div> <!-- ssm_lp_compare_row_1_features -->
<div class="ssm_lp_compare_row_1_features">Advanced Widget Settings</div> <!-- ssm_lp_compare_row_1_features -->
<div class="ssm_lp_compare_row_1_features">Define Slide Transition Animation for each widget</div> <!-- ssm_lp_compare_row_1_features -->
<div class="ssm_lp_compare_row_1_features">Enable or disable easing option for each widget</div> <!-- ssm_lp_compare_row_1_features -->
</div> <!-- right -->
</div> <!-- ssm_lp_f_group -->
<div id="ssm_lp_f_group">
<div class="left" style="padding-top: 189px;padding-bottom: 187px;">
Short Code Support
</div> <!-- left -->
<div class="right">
<div class="ssm_lp_compare_row_1_features">Multiple instances</div> <!-- ssm_lp_compare_row_1_features -->
<div class="ssm_lp_compare_row_1_features">Simple shortcode automatic generator</div> <!-- ssm_lp_compare_row_1_features -->
<div class="ssm_lp_compare_row_1_features">Define  FadeIn and FadeOut for each Shortcode </div> <!-- ssm_lp_compare_row_1_features -->
<div class="ssm_lp_compare_row_1_features">Define transition Interval for each Shortcode</div> <!-- ssm_lp_compare_row_1_features -->
<div class="ssm_lp_compare_row_1_features">Advanced ShortCode generator</div> <!-- ssm_lp_compare_row_1_features -->
<div class="ssm_lp_compare_row_1_features">Define Slideshow Size for each Shortcode</div> <!-- ssm_lp_compare_row_1_features -->
<div class="ssm_lp_compare_row_1_features">Define Slide Transition Animation for each Shortcode</div> <!-- ssm_lp_compare_row_1_features -->
<div class="ssm_lp_compare_row_1_features">Separate SpeedIn and SpeedOut for each Shortcode</div> <!-- ssm_lp_compare_row_1_features -->
<div class="ssm_lp_compare_row_1_features">Separate Slide transition interval for each Shortcode</div> <!-- ssm_lp_compare_row_1_features -->
<div class="ssm_lp_compare_row_1_features" style="font-size:12px;">Enable or disable easing option for each Shortcode</div> <!-- ssm_lp_compare_row_1_features -->
<div class="ssm_lp_compare_row_1_features" style="font-size:12px;">Enable or disable pause on hover for each Shortcode</div> <!-- ssm_lp_compare_row_1_features -->
<div class="ssm_lp_compare_row_1_features" style="font-size:12px;">Enable or disable synchronization for each Shortcode</div> <!-- ssm_lp_compare_row_1_features -->
</div> <!-- right -->
</div> <!-- ssm_lp_f_group -->
<div id="ssm_lp_f_group">
<div class="left" style="padding-top: 187px;padding-bottom: 189px;">
PHP Code Support
</div> <!-- left -->
<div class="right">
<div class="ssm_lp_compare_row_1_features">Use Outside loop</div> <!-- ssm_lp_compare_row_1_features -->
<div class="ssm_lp_compare_row_1_features">Multiple instances</div> <!-- ssm_lp_compare_row_1_features -->
<div class="ssm_lp_compare_row_1_features">Simple php code  support</div> <!-- ssm_lp_compare_row_1_features -->
<div class="ssm_lp_compare_row_1_features">Define  FadeIn and FadeOut for each PHP Code </div> <!-- ssm_lp_compare_row_1_features -->
<div class="ssm_lp_compare_row_1_features">Define transition Interval for each PHP Code</div> <!-- ssm_lp_compare_row_1_features -->
<div class="ssm_lp_compare_row_1_features">Define Slideshow Size for each PHP Code</div> <!-- ssm_lp_compare_row_1_features -->
<div class="ssm_lp_compare_row_1_features">Define Slide Transition Animation for each PHP Code</div> <!-- ssm_lp_compare_row_1_features -->
<div class="ssm_lp_compare_row_1_features">Separate SpeedIn and SpeedOut for each PHP Code</div> <!-- ssm_lp_compare_row_1_features -->
<div class="ssm_lp_compare_row_1_features">Separate Slide transition interval for each PHP Code</div> <!-- ssm_lp_compare_row_1_features -->
<div class="ssm_lp_compare_row_1_features" style="font-size:12px;">Enable or disable easing option for each PHP Code</div> <!-- ssm_lp_compare_row_1_features -->
<div class="ssm_lp_compare_row_1_features" style="font-size:12px;">Enable or disable pause on hover for each PHP Code</div> <!-- ssm_lp_compare_row_1_features -->
<div class="ssm_lp_compare_row_1_features" style="font-size:12px;">Enable or disable synchronization for each PHP Code</div> <!-- ssm_lp_compare_row_1_features -->
</div> <!-- right -->
</div> <!-- ssm_lp_f_group -->
</div> <!-- row_1 -->
<div class="row_2">
<div class="ssm_lp_compare_row_2_1"></div> <!-- ssm_lp_compare_row_2_1 -->
<div class="row_2_border" style="border-bottom: 1px solid lightgray; min-height: 3180px;">
<a href="http://wordpress.org/extend/plugins/simple-slideshow-manager/" target="_blank"><div class="ssm_lp_compare_row_2_2"></div></a> <!-- ssm_lp_compare_row_2_1 -->
<div class="y"></div>
<div class="y"></div>
<div class="y"></div>
<div class="y"></div>
<div class="n"></div>
<div class="n"></div>
<div class="y"></div>
<div class="y"></div>
<div class="y"></div>
<div class="y"></div>
<div class="y"></div>
<div class="n"></div>
<div class="n"></div>
<div class="n"></div>
<div class="n"></div>
<div class="n"></div>
<div class="y"></div>
<div class="y"></div>
<div class="n"></div>
<div class="n"></div>
<div class="n"></div>
<div class="n"></div>
<div class="n"></div>
<div class="y"></div>
<div class="n"></div>
<div class="n"></div>
<div class="n"></div>
<div class="n"></div>
<div class="n"></div>
<div class="n"></div>
<div class="n"></div>
<div class="n"></div>
<div class="n"></div>
<div class="n"></div>
<div class="n"></div>
<div class="n"></div>
<div class="n"></div>
<div class="n"></div>
<div class="n"></div>
<div class="n"></div>
<div class="n"></div>
<div class="n"></div>
<div class="n"></div>
<div class="n"></div>
<div class="n"></div>
<div class="n"></div>
<div class="n"></div>
<div class="n"></div>
<div class="n"></div>
<div class="n"></div>
<div class="y"></div>
<div class="y"></div>
<div class="y"></div>
<div class="n"></div>
<div class="n"></div>
<div class="n"></div>
<div class="y"></div>
<div class="y"></div>
<div class="y"></div>
<div class="y"></div>
<div class="y"></div>
<div class="y"></div>
<div class="y"></div>
<div class="y"></div>
<div class="n"></div>
<div class="n"></div>
<div class="n"></div>
<div class="n"></div>
<div class="n"></div>
<div class="y"></div>
<div class="y"></div>
<div class="n"></div>
<div class="n"></div>
<div class="n"></div>
<div class="y"></div>
<div class="n"></div>
<div class="n"></div>
<div class="n"></div>
<div class="n"></div>
<div class="n"></div>
<div class="n"></div>
<div class="y"></div>
<div class="n"></div>
<div class="y"></div>
<div class="n"></div>
<div class="n"></div>
<div class="y"></div>
<div class="n"></div>
<div class="n"></div>
<div class="n"></div>
<div class="n"></div>
<div class="n"></div>
<div class="n" style="border-bottom:0px;"></div>
</div> <!-- row_2_border -->
</div> <!-- row_2 -->
<div class="row_3">
<div class="ssm_lp_compare_row_3_1"></div> <!-- ssm_lp_compare_row_3_1 -->
<div class="row_3_shadow" style="border-bottom: 1px solid lightgray; min-height: 3204px;">
<a href="http://clients.acurax.com/advanced-slideshow-manager.php?utm_source=wpadmin&utm_medium=button&utm_campaign=compare"><div class="ssm_lp_compare_row_3_2_asm"></div></a> <!-- ssm_lp_compare_row_3_2 -->
<div class="y"></div> <!-- y -->
<div class="y"></div> <!-- y -->
<div class="y"></div> <!-- y -->
<div class="y"></div> <!-- y -->
<div class="y"></div> 
<div class="y"></div> 
<div class="y"></div> 
<div class="y"></div> 
<div class="y"></div> <!-- y -->
<div class="y"></div> <!-- y -->
<div class="y"></div> <!-- y -->
<div class="y"></div> <!-- y -->
<div class="y"></div> <!-- y -->
<div class="y"></div> <!-- y -->
<div class="y"></div> <!-- y -->
<div class="y"></div> <!-- y -->
<div class="y"></div> <!-- y -->
<div class="y"></div> <!-- y -->
<div class="y"></div> <!-- y -->
<div class="y"></div> <!-- y -->
<div class="y"></div> <!-- y -->
<div class="y"></div> <!-- y -->
<div class="y"></div> <!-- y -->
<div class="y"></div> <!-- y -->
<div class="y"></div> <!-- y -->
<div class="y"></div> <!-- y -->
<div class="y"></div> <!-- y -->
<div class="y"></div> <!-- y -->
<div class="y"></div> <!-- y -->
<div class="y"></div> <!-- y -->
<div class="y"></div> <!-- y -->
<div class="y"></div> <!-- y -->
<div class="y"></div> <!-- y -->
<div class="y"></div> <!-- y -->
<div class="y"></div> <!-- y -->
<div class="y"></div> <!-- y -->
<div class="y"></div> <!-- y -->
<div class="y"></div> <!-- y -->
<div class="y"></div> <!-- y -->
<div class="y"></div> <!-- y -->
<div class="y"></div> <!-- y -->
<div class="y"></div> <!-- y -->
<div class="y"></div> <!-- y -->
<div class="y"></div> <!-- y -->
<div class="y"></div> <!-- y -->
<div class="y"></div> <!-- y -->
<div class="y"></div> <!-- y -->
<div class="y"></div> <!-- y -->
<div class="y"></div> <!-- y -->
<div class="y"></div> <!-- y -->
<div class="y"></div> <!-- y -->
<div class="y"></div> <!-- y -->
<div class="y"></div> <!-- y -->
<div class="y"></div> <!-- y -->
<div class="y"></div> <!-- y -->
<div class="y"></div> <!-- y -->
<div class="y"></div> <!-- y -->
<div class="y"></div> <!-- y -->
<div class="y"></div> <!-- y -->
<div class="y"></div> <!-- y -->
<div class="y"></div> <!-- y -->
<div class="y"></div> <!-- y -->
<div class="y"></div> <!-- y -->
<div class="y"></div> <!-- y -->
<div class="y"></div> <!-- y -->
<div class="y"></div> <!-- y -->
<div class="y"></div> <!-- y -->
<div class="y"></div> <!-- y -->
<div class="y"></div> <!-- y -->
<div class="y"></div> <!-- y -->
<div class="y"></div> <!-- y -->
<div class="y"></div> <!-- y -->
<div class="y"></div> <!-- y -->
<div class="y"></div> <!-- y -->
<div class="y"></div> <!-- y -->
<div class="y"></div> <!-- y -->
<div class="y"></div> <!-- y -->
<div class="y"></div> <!-- y -->
<div class="y"></div> <!-- y -->
<div class="y"></div> <!-- y -->
<div class="y"></div> <!-- y -->
<div class="y"></div> <!-- y -->
<div class="y"></div> <!-- y -->
<div class="y"></div> <!-- y -->
<div class="y"></div> <!-- y -->
<div class="y"></div> <!-- y -->
<div class="y"></div> <!-- y -->
<div class="y"></div> <!-- y -->
<div class="y"></div> <!-- y -->
<div class="y"></div> <!-- y -->
<div class="y"></div> <!-- y -->
<div class="y"></div> <!-- y -->
<div class="y" style="border-bottom:0px;"></div> <!-- y -->
</div> <!-- row_3_shadow -->
</div> <!-- row_3 -->
<div id="ad_ssm_2_button_order" style="float:left;margin-left: 360px;">
<a href="http://clients.acurax.com/advanced-slideshow-manager.php?utm_source=plugin_ssm_settings&utm_medium=banner&utm_campaign=plugin_yellow_order" target="_blank"><div id="ad_ssm_2_button_order_link"></div></a></div> <!-- ad_ssm_2_button_order --></div> <!-- ssm_lp_compare -->
';
$ad_2='<div id="ad_ssm_2"> <a href="http://clients.acurax.com/advanced-slideshow-manager.php?utm_source=plugin_ssm_settings&utm_medium=banner&utm_campaign=plugin_enjoy" target="_blank"><div id="ad_ssm_2_button"></div></a> </div> <!-- ad_ssm_2 --><br>
<div id="ad_ssm_2_button_order">
<a href="http://clients.acurax.com/advanced-slideshow-manager.php?utm_source=plugin_ssm_settings&utm_medium=banner&utm_campaign=plugin_yellow_order" target="_blank"><div id="ad_ssm_2_button_order_link"></div></a></div> <!-- ad_ssm_2_button_order --> ';
if($ad=="" || $ad == 2) { echo $ad_2; } else if ($ad == 1) { echo $ad_1; } else { echo $ad_2; } 
} // Updated
function acx_ssm_admin_style()  // Adding Style For Admin
{
global $acx_si_fsmi_menu_highlight;
	echo '<link rel="stylesheet" type="text/css" href="' .plugins_url('style_admin.css', __FILE__). '">';
}	add_action('admin_head', 'acx_ssm_admin_style'); // ADMIN
?>