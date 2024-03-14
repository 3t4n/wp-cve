<?php
require_once('fergcorp_milestone.php');

/**
 * Milestone Tests
 */

class MilestoneTest extends WP_UnitTestCase {
    public $plugin_slug = 'milestone';
	private $plugin;
	
	//TOTEST:
	/* Input validation? Only test valid inputs...don't test for invalid?
	 * Test date manipulation
	 * Test Defaults
	 * Test blanks (e.g. database corruption)
	 * 
	*/
	  
	

    public function setUp() {
        parent::setUp();
		$GLOBALS['milestone'] = new Fergcorp_Milestone_Widget();
        $this->plugin = $GLOBALS['milestone'];
		
    }
	
	public function testForm(){
			
		$instance = "";
		$this->expectOutputRegex("/<div class=\"milestone-widget\">(.*?)<p>(.*?)<\/p>(.*?)<p>(.*?)<\/p>(.*?)<fieldset>(.*?)<\/fieldset>(.*?)<p>(.*?)<\/p>(.*?)<\/div>/s");
		$this->plugin->form($instance);
		
		$instance = array(
						'title' => "My Title",
						'event' => "My Event",
						'month' => 1,
						'day' => 31,
						'year' => 2015,
						'hour' => 12,
						'minute' => 30,
						'message' => "Happy Birthday!"
						);
								
		$this->expectOutputRegex("/<div class=\"milestone-widget\">(.*?)<p>(.*?)<\/p>(.*?)<p>(.*?)<\/p>(.*?)<fieldset>(.*?)<\/fieldset>(.*?)<p>(.*?)<\/p>(.*?)<\/div>/s");
		$this->plugin->form($instance);
		
		
	}
	
	public function testWidget(){
		
		$instance = array(
				'title' => "My Title",
				'event' => "My Event",
				'month' => 1,
				'day' => 31,
				'year' => 2015,
				'hour' => 12,
				'minute' => 30,
				'message' => "Happy Birthday!"
				);
				
		$args = array(
				'name' => 'Main Sidebar',
				'id' =>'sidebar-1',
				'description' => '',
				'class' => '',
				'before_widget' => '<aside id="fergcorp_milestone-2" class="widget widget_fergcorp_milestone">',
				'after_widget' => '</aside>',
				'before_title' => '<h3 class="widget-title">',
				'after_title' => '</h3>',
				'widget_id' => 'fergcorp_milestone-2',
				'widget_name' => 'Milestone',
				);
							
		$this->expectOutputRegex("/<aside id=\"fergcorp_milestone-2\" class=\"widget widget_fergcorp_milestone\">(.*?)<h3(.*?)>(.*?)<\/h3><div (.*?)>(.*?)<div (.*?)>(.*?)<strong (.*?)>(.*?)<\/strong>(.*?)<span (.*?)>(.*?)<\/span>(.*?)<\/div>(.*?)<div (.*?)><span (.*?)>(.*?)<\/span>(.*?)<span (.*?)>(.*?)<\/span>(.*?)<\/div>(.*?)<\/div>(.*?)<\/aside>/s");
		$this->plugin->widget($args, $instance);
		
		
				$instance = array(
				'title' => "My Title",
				'event' => "My Event",
				'month' => 1,
				'day' => 31,
				'year' => 2010,
				'hour' => 12,
				'minute' => 30,
				'message' => "Happy Birthday!"
				);
				
		$args = array(
				'name' => 'Main Sidebar',
				'id' =>'sidebar-1',
				'description' => '',
				'class' => '',
				'before_widget' => '<aside id="fergcorp_milestone-2" class="widget widget_fergcorp_milestone">',
				'after_widget' => '</aside>',
				'before_title' => '<h3 class="widget-title">',
				'after_title' => '</h3>',
				'widget_id' => 'fergcorp_milestone-2',
				'widget_name' => 'Milestone',
				);
							
		$this->expectOutputRegex("/<aside id=\"fergcorp_milestone-2\" class=\"widget widget_fergcorp_milestone\">(.*?)<h3(.*?)>(.*?)<\/h3><div (.*?)>(.*?)<div (.*?)>(.*?)<strong (.*?)>(.*?)<\/strong>(.*?)<span (.*?)>(.*?)<\/span>(.*?)<\/div>(.*?)<div (.*?)><span (.*?)>(.*?)<\/span>(.*?)<span (.*?)>(.*?)<\/span>(.*?)<\/div>(.*?)<\/div>(.*?)<\/aside>/s");
		$this->plugin->widget($args, $instance);
		
		
		
	}
	
	public function testScript(){
		fergcorp_milestone_script();
	}
	
	public function testRegisterWidget(){
		fergcorp_milestone_register_widgets();
	}

	public function testUnitCalculationSeconds(){
		
		$eventDate = new DateTime();
		$eventDate->setDate(2015, 1, 31);
		$eventDate->setTime(12, 30, 0);
		
		$time = new DateTime();
		$time->setDate(2015, 1, 31);
		$time->setTime(12, 30, 0);
		
		for($i = 0; $i < 59; $i++){
				
			$time->sub(new DateInterval("PT1S"));
				
			$eventDiff = $eventDate->getTimestamp() - $time->getTimestamp();
			
			$calculate_units = $this->plugin->calculate_units($eventDiff, $eventDate->getTimestamp());
			
			$this->assertLessThanOrEqual(60, $calculate_units["value"]);
			$this->assertStringStartsWith("second", $calculate_units["unit"]);
		}
	}
	
		public function testUnitCalculationMinutes(){
		
		$eventDate = new DateTime();
		$eventDate->setDate(2015, 1, 31);
		$eventDate->setTime(12, 30, 0);
		
		$time = new DateTime();
		$time->setDate(2015, 1, 31);
		$time->setTime(12, 30, 0);
		
		for($i = 0; $i < 59; $i++){
				
			$time->sub(new DateInterval("PT1M"));
				
			$eventDiff = $eventDate->getTimestamp() - $time->getTimestamp();
			
			$calculate_units = $this->plugin->calculate_units($eventDiff, $eventDate->getTimestamp());
			
			$this->assertLessThanOrEqual(60, $calculate_units["value"]);
			$this->assertStringStartsWith("minute", $calculate_units["unit"]);
		}
	}
		
		public function testUnitCalculationHours(){
		
		$eventDate = new DateTime();
		$eventDate->setDate(2015, 1, 31);
		$eventDate->setTime(12, 30, 0);
		
		$time = new DateTime();
		$time->setDate(2015, 1, 31);
		$time->setTime(12, 30, 0);
		
		for($i = 0; $i < 23; $i++){
				
			$time->sub(new DateInterval("PT1H"));
				
			$eventDiff = $eventDate->getTimestamp() - $time->getTimestamp();
			
			$calculate_units = $this->plugin->calculate_units($eventDiff, $eventDate->getTimestamp());
			
			$this->assertLessThanOrEqual(23, $calculate_units["value"]);
			$this->assertStringStartsWith("hour", $calculate_units["unit"]);
		}
	}
		
	public function testUnitCalculationDays(){
		
		$eventDate = new DateTime();
		$eventDate->setDate(2015, 1, 31);
		$eventDate->setTime(12, 30, 0);
		
		$time = new DateTime();
		$time->setDate(2015, 1, 31);
		$time->setTime(12, 30, 0);
		
		for($i = 1; $i < date("t", $eventDate->getTimestamp()); $i++){
				
			$time->sub(new DateInterval("P1D"));
				
			$eventDiff = $eventDate->getTimestamp() - $time->getTimestamp();
			
			$calculate_units = $this->plugin->calculate_units($eventDiff, $eventDate->getTimestamp());
			
			$this->assertLessThanOrEqual($eventDate->getTimestamp(), $calculate_units["value"]);
			$this->assertStringStartsWith("day", $calculate_units["unit"]);
		}
	}
	
		public function testUnitCalculationMonths(){
		
		$eventDate = new DateTime();
		$eventDate->setDate(2015, 1, 31);
		$eventDate->setTime(12, 30, 0);
		
		$time = new DateTime();
		$time->setDate(2015, 1, 31);
		$time->setTime(12, 30, 0);
		
		for($i = 1; $i <= 12; $i++){
				
			$time->sub(new DateInterval("P1M"));
				
			$eventDiff = $eventDate->getTimestamp() - $time->getTimestamp();
			
			$calculate_units = $this->plugin->calculate_units($eventDiff, $eventDate->getTimestamp());
			
			$this->assertLessThanOrEqual($eventDate->getTimestamp(), $calculate_units["value"]);
			$this->assertStringStartsWith("month", $calculate_units["unit"]);
		}
	}
		
	public function testUnitCalculationYears(){
		
		$eventDate = new DateTime();
		$eventDate->setDate(2015, 1, 31);
		$eventDate->setTime(12, 30, 0);
		
		$time = new DateTime();
		$time->setDate(2015, 1, 31);
		$time->setTime(12, 30, 0);
		
		for($i = 0; $i <= 10; $i++){
				
			$time->sub(new DateInterval("P1Y"));
				
			$eventDiff = $eventDate->getTimestamp() - $time->getTimestamp();
			
			$calculate_units = $this->plugin->calculate_units($eventDiff, $eventDate->getTimestamp());

			$this->assertLessThanOrEqual($eventDate->getTimestamp(), $calculate_units["value"]);
			$this->assertStringStartsWith("year", $calculate_units["unit"]);
		}
	}
	
	public function testUpdate(){

		$oldinstance = array();
		$newinstance = array(
								'title' => "My Title",
								'event' => "My Event",
								'month' => 1,
								'day' => 31,
								'year' => 2015,
								'hour' => 12,
								'minute' => 30,
								'message' => "Happy Birthday!"
								);
								
		$resultUpdate = $this->plugin->update($newinstance, $oldinstance);
		
		$this->assertTrue( is_array( $resultUpdate ) );
		
		foreach ($newinstance as $name => $value) {
			$this->assertTrue(isset($resultUpdate[$name]));
			$this->assertEquals($value, $resultUpdate[$name]);
		}
		
		//Month
		for($i=1; $i<12; $i++){
			$newinstance["month"] = $i; //Set new year
			$resultUpdate = $this->plugin->update($newinstance, $oldinstance); //Update instance
			$this->assertEquals($i, $resultUpdate["month"]);
		}
		
		//Day
		for($i=1; $i<31; $i++){
			$newinstance["day"] = $i; //Set new year
			$resultUpdate = $this->plugin->update($newinstance, $oldinstance); //Update instance
			$this->assertEquals($i, $resultUpdate["day"]);
		}
				
		//Year
		for($i=2000; $i<2100; $i++){
			$newinstance["year"] = $i; //Set new year
			$resultUpdate = $this->plugin->update($newinstance, $oldinstance); //Update instance
			$this->assertEquals($i, $resultUpdate["year"]);
		}
		
		//Hour
		for($i=1; $i<60; $i++){
			$newinstance["hour"] = $i; //Set new year
			$resultUpdate = $this->plugin->update($newinstance, $oldinstance); //Update instance
			$this->assertEquals($i, $resultUpdate["hour"]);
		}
		
		//Minute
		for($i=1; $i<60; $i++){
			$newinstance["minute"] = $i; //Set new year
			$resultUpdate = $this->plugin->update($newinstance, $oldinstance); //Update instance
			$this->assertEquals($i, $resultUpdate["minute"]);
		}
		
		
	}

   /*public function testAppendContent() {
        $this->assertEquals( "<p>Hello WordPress Unit Tests</p>", $this->my_plugin->append_content(''), '->append_content() appends text' );
    }

    /**
     * A contrived example using some WordPress functionality
     */
     /*
    public function testPostTitle() {
        // This will simulate running WordPress' main query.
        // See wordpress-tests/lib/testcase.php
        $this->go_to('http://localhost/~andrewferguson/wordpress/?p=1');

        // Now that the main query has run, we can do tests that are more functional in nature
        global $wp_query;
        $post = $wp_query->get_queried_object();
        $this->assertEquals('Hello world!', $post->post_title );
    }*/


}
