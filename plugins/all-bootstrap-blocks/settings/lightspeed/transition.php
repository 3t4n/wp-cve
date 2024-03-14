<?php
/*
Name: 9. Transition
Slug: transition
Description: Transitions animate elements of the page in as a user scrolss down the page. It can also animate elements out as a user navigates from one page to another to provide a smooth look and feel when navigating your site.
Position: 20
Theme: 
*/
$slug = AREOI__PREPEND . ( !empty( $section ) ? '-' . $section : '' )  . '-transition-';

return array(
	array(
		'label' => 'Transition',
		'name' => $slug . 'transition',
		'variable' => '',
		'row' => 'default',
		'input' => 'select',
		'default' => null,
		'description' => 'Transition elements in from different directions using different effects.',
		'allow_reset' => false,
		'options' => array(
			array(
				'id' => null,
				'label' => 'None'
			),
			array(
				'id' => 'up',
				'label' => 'Up'
			),
			array(
				'id' => 'right',
				'label' => 'Right'
			),
			array(
				'id' => 'down',
				'label' => 'Down'
			),
			array(
				'id' => 'left',
				'label' => 'Left'
			),
			array(
				'id' => 'grow',
				'label' => 'Grow'
			),
			array(
				'id' => 'shrink',
				'label' => 'Shrink'
			),
			array(
				'id' => 'blur',
				'label' => 'Blur'
			),
		)
	),
	array(
		'label' => 'Page Transition',
		'name' => $slug . 'page',
		'variable' => '',
		'row' => 'default',
		'input' => 'checkbox',
		'default' => false,
		'description' => 'If checked elements will animate out before a user moves to the next page.',
		'allow_reset' => false,
		'options' => array()
	),
	array(
		'label' => 'Background Transition',
		'name' => $slug . 'background',
		'variable' => '',
		'row' => 'default',
		'input' => 'checkbox',
		'default' => false,
		'description' => 'If checked background colours will animate from one to the next as you scroll.',
		'allow_reset' => false,
		'options' => array()
	),
	array(
		'label' => 'Hover Transition',
		'name' => $slug . 'hover',
		'variable' => '',
		'row' => 'default',
		'input' => 'select',
		'default' => null,
		'description' => 'Apply transitions when hovering over elements that have urls applied.',
		'allow_reset' => false,
		'options' => array(
			array(
				'id' => null,
				'label' => 'None'
			),
			array(
				'id' => 'up',
				'label' => 'Up'
			),
			array(
				'id' => 'right',
				'label' => 'Right'
			),
			array(
				'id' => 'down',
				'label' => 'Down'
			),
			array(
				'id' => 'left',
				'label' => 'Left'
			),
			array(
				'id' => 'grow',
				'label' => 'Grow'
			),
			array(
				'id' => 'shrink',
				'label' => 'Shrink'
			),
		)
	),
);