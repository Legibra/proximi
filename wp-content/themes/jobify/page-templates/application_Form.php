<?php

	/**
	 * Template Name: Page: Application_form
	 *
	 * @package Jobify
	 * @since Jobify 1.0
	 */

	get_header();
    
?>
	<div class="row" >
		<div class="col-lg-8 col-lg-offset-2">

			<?php

				if ($_SERVER["REQUEST_METHOD"] == "POST"){
			        if (!empty($_POST["id_card"]))
			            $id_card=$_POST["id_card"];
			        if (!empty($_POST["phone_number"]))
			            $phone_number=$_POST["phone_number"];
			        if (!empty($_POST["email"]))
			            $email=$_POST["email"];
			        if (!empty($_FILE["cv_upload"]))
			            $cv_upload=$_FILE["cv_upload"];
			        	

			        $job_id = 3;

			        $i = 1;
			        $post_title = $i++;

			        $my_post = array(
			            'post_title' => $post_title,
			            'post_status'   => 'publish',
			            'post_author'   => 1,
			            'post_type' => 'Application'
			        );

			        $post_id = wp_insert_post($my_post);


			        if ($post_id){
			       
			        	update_field("field_59ca2d22eb519", $job_id,$post_id);
			            update_field("field_59ca1f6a9149b", $id_card,$post_id);
			            update_field("field_59ca1fc03f4a6", $phone_number,$post_id);
			            update_field("field_59ca1fe0702fd", $email,$post_id);
			            //update_field("field_59ca2339f2102", $cv_upload,$post_id);

        				$att = my_update_attachment('user_photo',$post-&gt;ID);
						update_field('field_59ca2339f2102',$att['attach_id'],$post-&gt;ID);//change {field_key} to actual key
					}
			?>

			<form class="form" id="apply_form" method="POST" action="<?php the_permalink(); ?>" enctype="multipart/form-data">
			  <div class="form-group">
			    <label for="id_card">ID Number</label>
			    <input type="text" class="form-control" id="id_card" name="id_card" placeholder="" value="<?php echo $id; ?> ">
			  </div>
			  <div class="form-group">
			    <label for="phone_number">Phone Number</label>
			    <input type="text" class="form-control" id="phone_number" name="phone_number" placeholder="">
			  </div>
			  <div class="form-group">
			    <label for="email">Email address</label>
			    <input type="email" class="form-control" id="email" name="email" placeholder="">
			  </div>
			  <div class="form-group">
			    <label for="cv_upload">Upload CV</label>
			    <input type="file" class="form-control" id="cv_upload" name="cv_upload" placeholder="">
			  </div>
			  <div class="">
			  	<button class="button">Apply this Job</button>
			  </div>
			</form>
		</div>	
	</div> 	


<?php get_footer(); ?>