<?php
/*
Plugin Name: Rhymebox Widget
Plugin URI: http://www.rhymebox.de/blog/rhmyebox-widget/
Description: A Rhyming dictionary at your fingertips
Author: Nicolas Kuttler
Version: 0.2.4
Author URI: http://www.rhymebox.de/
*/

function Rhymebox_init() {
	register_sidebar_widget(__('Rhymebox'), 'widget_Rhymebox');
	register_widget_control('Rhymebox', 'Rhymebox_control', 300, 200 );
}
add_action("plugins_loaded", "Rhymebox_init");

// Install hook
register_activation_hook(__FILE__,'rhymebox_widget_install');
function rhymebox_widget_install() {
	if (!get_option('widget_Rhymebox')) {
		$options['title'] = "Rhyme";
		$options['language'] = "english";
		$options['homelink'] = "No";
		update_option("widget_Rhymebox", $options);
	}
}

function widget_Rhymebox($args) {
	$options = get_option("widget_Rhymebox");
	$word = 'Rhyme search';
	// default rhyme
	if ($options['language'] == 'german') {
		$word = 'Reim';
	}

	extract($args);
	echo $before_widget;
	echo $before_title;
	echo $options['title'];
	echo $after_title;
	?>
	<ul>
		<li>
		<form id="rhymebox-widget" action="http://www.rhymebox.de/">
			<input type="hidden" name="l" value="<?php
				switch ($options['language']) {
					default:
					case 'english':
						echo 'en';
						break;
					case 'german':
						echo 'de';
						break;
				}
			?>" />
			<input type="hidden" name="m" value="words" />
			<input id="rhymebox-widget-search" type="text" size="10" name="s" value="<?php echo $word; ?>" onfocus="if (this.value == '<?php echo $word; ?>') {this.value=''}" style="margin: 2px 0;" />
			<input type="submit" value="<?php echo $word ?>"/>
	
		</form>
		<?php
			if ($options['homelink'] !== 'Yes') {
				switch ($options['language']) {
					default:
					case 'english':
						echo 'Powered by';
						break;
					case 'german':
						echo 'Unterstützt von';
						break;
				}
				echo ' <a href="http://www.rhymebox.com">rhymebox</a>';
			}
		?>
		</li>
	</ul>
	<?php
	echo $after_widget;
}

function Rhymebox_control() {
	$options = get_option("widget_Rhymebox");

	if ($_POST['Rhymebox-Submit']) {
		$options['title'] = htmlspecialchars($_POST['title']);
		$options['language'] = $_POST['language'];
		$options['homelink'] = $_POST['homelink'];
		update_option("widget_Rhymebox", $options);
	}
	?>
	<p>
		<label for="title">Widget Title: </label>
		<input type="text" id="title" name="title" value="<?php echo $options['title'];?>" />
		<br />
		<label for="language">Rhyme in: </label>
		<select id="language" name="language">
		<option <?php
			if ($options['language'] == 'english') { echo 'selected'; }
		?>>english</option>
			<option <?php
			if ($options['language'] == 'german') { echo 'selected'; }
		?>>german</option>
		</select>
		<br />
		<label for="homelink">Hide the link to rhymebox.de</label>
		<select id="homelink" name="homelink">
		<option <?php
			if ($options['homelink'] == 'Yes') { echo 'selected'; }
		?>>Yes</option>
		<option <?php
			if ($options['homelink'] == 'No') { echo 'selected'; }
		?>>No</option>
		</select>
		<input type="hidden" id="Rhymebox-Submit" name="Rhymebox-Submit" value="1" />
	</p>
	<?php
}

