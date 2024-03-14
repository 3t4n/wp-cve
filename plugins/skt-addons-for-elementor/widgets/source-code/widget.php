<?php
/**
 * Source Code
 *
 * @package Skt_Addons_Elementor
 */

namespace Skt_Addons_Elementor\Elementor\Widget;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;

defined('ABSPATH') || die();

class Source_Code extends Base {

	/**
	 * Get widget title.
	 *
	 * @return string Widget title.
	 * @since 1.0
	 * @access public
	 *
	 */
	public function get_title() {
		return __('Source Code', 'skt-addons-elementor');
	}

	/**
	 * Get widget icon.
	 *
	 * @return string Widget icon.
	 * @since 1.0
	 * @access public
	 *
	 */
	public function get_icon() {
		return 'skti skti-code-browser';
	}

	public function get_keywords() {
		return ['source-code', 'source', 'code'];
	}

	public function lng_type() {
		return [
			'markup' => __('HTML Markup', 'skt-addons-elementor'),
			'css' => __('CSS', 'skt-addons-elementor'),
			'clike' => __('Clike', 'skt-addons-elementor'),
			'javascript' => __('JavaScript', 'skt-addons-elementor'),
			'abap' => __('ABAP', 'skt-addons-elementor'),
			'abnf' => __('Augmented Backus–Naur form', 'skt-addons-elementor'),
			'actionscript' => __('ActionScript', 'skt-addons-elementor'),
			'ada' => __('Ada', 'skt-addons-elementor'),
			'apacheconf' => __('Apache Configuration', 'skt-addons-elementor'),
			'apl' => __('APL', 'skt-addons-elementor'),
			'applescript' => __('AppleScript', 'skt-addons-elementor'),
			'arduino' => __('Arduino', 'skt-addons-elementor'),
			'arff' => __('ARFF', 'skt-addons-elementor'),
			'asciidoc' => __('AsciiDoc', 'skt-addons-elementor'),
			'asm6502' => __('6502 Assembly', 'skt-addons-elementor'),
			'aspnet' => __('ASP.NET (C#)', 'skt-addons-elementor'),
			'autohotkey' => __('AutoHotkey', 'skt-addons-elementor'),
			'autoit' => __('Autoit', 'skt-addons-elementor'),
			'bash' => __('Bash', 'skt-addons-elementor'),
			'basic' => __('BASIC', 'skt-addons-elementor'),
			'batch' => __('Batch', 'skt-addons-elementor'),
			'bison' => __('Bison', 'skt-addons-elementor'),
			'bnf' => __('Bnf', 'skt-addons-elementor'),
			'brainfuck' => __('Brainfuck', 'skt-addons-elementor'),
			'bro' => __('Bro', 'skt-addons-elementor'),
			'c' => __('C', 'skt-addons-elementor'),
			'csharp' => __('Csharp', 'skt-addons-elementor'),
			'cpp' => __('Cpp', 'skt-addons-elementor'),
			'cil' => __('Cil', 'skt-addons-elementor'),
			'coffeescript' => __('Coffeescript', 'skt-addons-elementor'),
			'cmake' => __('Cmake', 'skt-addons-elementor'),
			'clojure' => __('Clojure', 'skt-addons-elementor'),
			'crystal' => __('Crystal', 'skt-addons-elementor'),
			'csp' => __('Csp', 'skt-addons-elementor'),
			'css-extras' => __('Css-extras', 'skt-addons-elementor'),
			'd' => __('D', 'skt-addons-elementor'),
			'dart' => __('Dart', 'skt-addons-elementor'),
			'diff' => __('Diff', 'skt-addons-elementor'),
			'django' => __('Django', 'skt-addons-elementor'),
			'dns-zone-file' => __('Dns-zone-file', 'skt-addons-elementor'),
			'docker' => __('Docker', 'skt-addons-elementor'),
			'ebnf' => __('Ebnf', 'skt-addons-elementor'),
			'eiffel' => __('Eiffel', 'skt-addons-elementor'),
			'ejs' => __('Ejs', 'skt-addons-elementor'),
			'elixir' => __('Elixir', 'skt-addons-elementor'),
			'elm' => __('Elm', 'skt-addons-elementor'),
			'erb' => __('Erb', 'skt-addons-elementor'),
			'erlang' => __('Erlang', 'skt-addons-elementor'),
			'fsharp' => __('Fsharp', 'skt-addons-elementor'),
			'firestore-security-rules' => __('Firestore-security-rules', 'skt-addons-elementor'),
			'flow' => __('Flow', 'skt-addons-elementor'),
			'fortran' => __('Fortran', 'skt-addons-elementor'),
			'gcode' => __('Gcode', 'skt-addons-elementor'),
			'gdscript' => __('Gdscript', 'skt-addons-elementor'),
			'gedcom' => __('Gedcom', 'skt-addons-elementor'),
			'gherkin' => __('Gherkin', 'skt-addons-elementor'),
			'git' => __('Git', 'skt-addons-elementor'),
			'glsl' => __('Glsl', 'skt-addons-elementor'),
			'gml' => __('Gml', 'skt-addons-elementor'),
			'go' => __('Go', 'skt-addons-elementor'),
			'graphql' => __('Graphql', 'skt-addons-elementor'),
			'groovy' => __('Groovy', 'skt-addons-elementor'),
			'haml' => __('Haml', 'skt-addons-elementor'),
			'handlebars' => __('Handlebars', 'skt-addons-elementor'),
			'haskell' => __('Haskell', 'skt-addons-elementor'),
			'haxe' => __('Haxe', 'skt-addons-elementor'),
			'hcl' => __('Hcl', 'skt-addons-elementor'),
			'http' => __('Http', 'skt-addons-elementor'),
			'hpkp' => __('Hpkp', 'skt-addons-elementor'),
			'hsts' => __('Hsts', 'skt-addons-elementor'),
			'ichigojam' => __('Ichigojam', 'skt-addons-elementor'),
			'icon' => __('Icon', 'skt-addons-elementor'),
			'inform7' => __('Inform7', 'skt-addons-elementor'),
			'ini' => __('Ini', 'skt-addons-elementor'),
			'io' => __('Io', 'skt-addons-elementor'),
			'j' => __('J', 'skt-addons-elementor'),
			'java' => __('Java', 'skt-addons-elementor'),
			'javadoc' => __('Javadoc', 'skt-addons-elementor'),
			'javadoclike' => __('Javadoclike', 'skt-addons-elementor'),
			'javastacktrace' => __('Javastacktrace', 'skt-addons-elementor'),
			'jolie' => __('Jolie', 'skt-addons-elementor'),
			'jq' => __('Jq', 'skt-addons-elementor'),
			'jsdoc' => __('Jsdoc', 'skt-addons-elementor'),
			'js-extras' => __('Js-extras', 'skt-addons-elementor'),
			'js-templates' => __('Js-templates', 'skt-addons-elementor'),
			'json' => __('Json', 'skt-addons-elementor'),
			'jsonp' => __('Jsonp', 'skt-addons-elementor'),
			'json5' => __('Json5', 'skt-addons-elementor'),
			'julia' => __('Julia', 'skt-addons-elementor'),
			'keyman' => __('Keyman', 'skt-addons-elementor'),
			'kotlin' => __('Kotlin', 'skt-addons-elementor'),
			'latex' => __('Latex', 'skt-addons-elementor'),
			'less' => __('Less', 'skt-addons-elementor'),
			'lilypond' => __('Lilypond', 'skt-addons-elementor'),
			'liquid' => __('Liquid', 'skt-addons-elementor'),
			'lisp' => __('Lisp', 'skt-addons-elementor'),
			'livescript' => __('Livescript', 'skt-addons-elementor'),
			'lolcode' => __('Lolcode', 'skt-addons-elementor'),
			'lua' => __('Lua', 'skt-addons-elementor'),
			'makefile' => __('Makefile', 'skt-addons-elementor'),
			'markdown' => __('Markdown', 'skt-addons-elementor'),
			'markup-templating' => __('Markup-templating', 'skt-addons-elementor'),
			'matlab' => __('Matlab', 'skt-addons-elementor'),
			'mel' => __('Mel', 'skt-addons-elementor'),
			'mizar' => __('Mizar', 'skt-addons-elementor'),
			'monkey' => __('Monkey', 'skt-addons-elementor'),
			'n1ql' => __('N1ql', 'skt-addons-elementor'),
			'n4js' => __('N4js', 'skt-addons-elementor'),
			'nand2tetris-hdl' => __('Nand2tetris-hdl', 'skt-addons-elementor'),
			'nasm' => __('Nasm', 'skt-addons-elementor'),
			'nginx' => __('Nginx', 'skt-addons-elementor'),
			'nim' => __('Nim', 'skt-addons-elementor'),
			'nix' => __('Nix', 'skt-addons-elementor'),
			'nsis' => __('Nsis', 'skt-addons-elementor'),
			'objectivec' => __('Objectivec', 'skt-addons-elementor'),
			'ocaml' => __('Ocaml', 'skt-addons-elementor'),
			'opencl' => __('Opencl', 'skt-addons-elementor'),
			'oz' => __('Oz', 'skt-addons-elementor'),
			'parigp' => __('Parigp', 'skt-addons-elementor'),
			'parser' => __('Parser', 'skt-addons-elementor'),
			'pascal' => __('Pascal', 'skt-addons-elementor'),
			'pascaligo' => __('Pascaligo', 'skt-addons-elementor'),
			'pcaxis' => __('Pcaxis', 'skt-addons-elementor'),
			'perl' => __('Perl', 'skt-addons-elementor'),
			'php' => __('Php', 'skt-addons-elementor'),
			'phpdoc' => __('Phpdoc', 'skt-addons-elementor'),
			'php-extras' => __('Php-extras', 'skt-addons-elementor'),
			'plsql' => __('Plsql', 'skt-addons-elementor'),
			'powershell' => __('Powershell', 'skt-addons-elementor'),
			'processing' => __('Processing', 'skt-addons-elementor'),
			'prolog' => __('Prolog', 'skt-addons-elementor'),
			'properties' => __('Properties', 'skt-addons-elementor'),
			'protobuf' => __('Protobuf', 'skt-addons-elementor'),
			'pug' => __('Pug', 'skt-addons-elementor'),
			'puppet' => __('Puppet', 'skt-addons-elementor'),
			'pure' => __('Pure', 'skt-addons-elementor'),
			'python' => __('Python', 'skt-addons-elementor'),
			'q' => __('Q', 'skt-addons-elementor'),
			'qore' => __('Qore', 'skt-addons-elementor'),
			'r' => __('R', 'skt-addons-elementor'),
			'jsx' => __('Jsx', 'skt-addons-elementor'),
			'tsx' => __('Tsx', 'skt-addons-elementor'),
			'renpy' => __('Renpy', 'skt-addons-elementor'),
			'reason' => __('Reason', 'skt-addons-elementor'),
			'regex' => __('Regex', 'skt-addons-elementor'),
			'rest' => __('Rest', 'skt-addons-elementor'),
			'rip' => __('Rip', 'skt-addons-elementor'),
			'roboconf' => __('Roboconf', 'skt-addons-elementor'),
			'ruby' => __('Ruby', 'skt-addons-elementor'),
			'rust' => __('Rust', 'skt-addons-elementor'),
			'sas' => __('Sas', 'skt-addons-elementor'),
			'sass' => __('Sass', 'skt-addons-elementor'),
			'scss' => __('Scss', 'skt-addons-elementor'),
			'scala' => __('Scala', 'skt-addons-elementor'),
			'scheme' => __('Scheme', 'skt-addons-elementor'),
			'shell-session' => __('Shell-session', 'skt-addons-elementor'),
			'smalltalk' => __('Smalltalk', 'skt-addons-elementor'),
			'smarty' => __('Smarty', 'skt-addons-elementor'),
			'soy' => __('Soy', 'skt-addons-elementor'),
			'splunk-spl' => __('Splunk-spl', 'skt-addons-elementor'),
			'sql' => __('Sql', 'skt-addons-elementor'),
			'stylus' => __('Stylus', 'skt-addons-elementor'),
			'swift' => __('Swift', 'skt-addons-elementor'),
			'tap' => __('Tap', 'skt-addons-elementor'),
			'tcl' => __('Tcl', 'skt-addons-elementor'),
			'textile' => __('Textile', 'skt-addons-elementor'),
			'toml' => __('Toml', 'skt-addons-elementor'),
			'tt2' => __('Tt2', 'skt-addons-elementor'),
			'turtle' => __('Turtle', 'skt-addons-elementor'),
			'twig' => __('Twig', 'skt-addons-elementor'),
			'typescript' => __('Typescript', 'skt-addons-elementor'),
			't4-cs' => __('T4-cs', 'skt-addons-elementor'),
			't4-vb' => __('T4-vb', 'skt-addons-elementor'),
			't4-templating' => __('T4-templating', 'skt-addons-elementor'),
			'vala' => __('Vala', 'skt-addons-elementor'),
			'vbnet' => __('Vbnet', 'skt-addons-elementor'),
			'velocity' => __('Velocity', 'skt-addons-elementor'),
			'verilog' => __('Verilog', 'skt-addons-elementor'),
			'vhdl' => __('Vhdl', 'skt-addons-elementor'),
			'vim' => __('Vim', 'skt-addons-elementor'),
			'visual-basic' => __('Visual-basic', 'skt-addons-elementor'),
			'wasm' => __('Wasm', 'skt-addons-elementor'),
			'wiki' => __('Wiki', 'skt-addons-elementor'),
			'xeora' => __('Xeora', 'skt-addons-elementor'),
			'xojo' => __('Xojo', 'skt-addons-elementor'),
			'xquery' => __('Xquery', 'skt-addons-elementor'),
			'yaml' => __('Yaml', 'skt-addons-elementor'),
		];
	}

	/**
     * Register widget content controls
     */
	protected function register_content_controls() {
		$this->__source_code_content_controls();
		$this->__custom_color_content_controls();
	}

	protected function __source_code_content_controls() {

		$this->start_controls_section(
			'_section_source_code',
			[
				'label' => __('Source Code', 'skt-addons-elementor'),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'lng_type',
			[
				'label' => __('Language Type', 'skt-addons-elementor'),
				'label_block' => true,
				'type' => Controls_Manager::SELECT,
				'default' => 'markup',
				'options' => $this->lng_type(),
			]
		);

		$this->add_control(
			'theme',
			[
				'label' => __('Theme', 'skt-addons-elementor'),
				'label_block' => true,
				'type' => Controls_Manager::SELECT,
				'default' => 'prism',
				'options' => [
					'prism' => __('Default', 'skt-addons-elementor'),
					'prism-coy' => __('Coy', 'skt-addons-elementor'),
					'prism-dark' => __('Dark', 'skt-addons-elementor'),
					'prism-funky' => __('Funky', 'skt-addons-elementor'),
					'prism-okaidia' => __('Okaidia', 'skt-addons-elementor'),
					'prism-solarizedlight' => __('Solarized light', 'skt-addons-elementor'),
					'prism-tomorrow' => __('Tomorrow', 'skt-addons-elementor'),
					'prism-twilight' => __('Twilight', 'skt-addons-elementor'),
					'custom' => __('Custom Color', 'skt-addons-elementor'),
				],
                'style_transfer' => true,
			]
		);

		$this->add_control(
			'source_code',
			[
				'label' => __('Source Code', 'skt-addons-elementor'),
				'type' => Controls_Manager::CODE,
				'rows' => 20,
				'default' => '<p class="random-text">Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p>',
				'placeholder' => __('Source Code....', 'skt-addons-elementor'),
				'condition' => [
					'lng_type!' => '',
				],
			]
		);
		$this->add_control(
			'copy_btn_text_show',
			[
				'label' => __('Copy Button Text Show?', 'skt-addons-elementor'),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'yes',
                'style_transfer' => true,
			]
		);
		$this->add_control(
			'copy_btn_text',
			[
				'label' => __('Copy Button Text', 'skt-addons-elementor'),
				'type' => Controls_Manager::TEXT,
				'rows' => 10,
				'default' => __('Copy to clipboard', 'skt-addons-elementor'),
				'placeholder' => __('Copy Button Text', 'skt-addons-elementor'),
				'condition' => [
					'copy_btn_text_show' => 'yes',
				],
			]
		);
		$this->add_control(
			'after_copy_btn_text',
			[
				'label' => __('After Copy Button Text', 'skt-addons-elementor'),
				'type' => Controls_Manager::TEXT,
				'rows' => 10,
				'default' => __('Copied', 'skt-addons-elementor'),
				'placeholder' => __('Copied', 'skt-addons-elementor'),
				'condition' => [
					'copy_btn_text_show' => 'yes',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function __custom_color_content_controls() {

		$this->start_controls_section(
			'_section_source_code_custom_color',
			[
				'label' => __('Custom Color', 'skt-addons-elementor'),
				'tab' => Controls_Manager::TAB_CONTENT,
				'condition' => [
					'theme' => 'custom',
				],
			]
		);
		$this->add_control(
			'custom_background',
			[
				'label' => __( 'Background Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .custom :not(pre) > code[class*="language-"],{{WRAPPER}} .custom pre[class*="language-"]' => 'background: {{VALUE}}',
				],
				'condition' => [
					'theme' => 'custom',
				],
			]
		);
		$this->add_control(
			'custom_text_color',
			[
				'label' => __( 'Text Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .custom code[class*="language-"],{{WRAPPER}} .custom pre[class*="language-"]' => 'color: {{VALUE}}',
				],
				'condition' => [
					'theme' => 'custom',
				],
			]
		);
		$this->add_control(
			'custom_text_shadow_color',
			[
				'label' => __( 'Text shadow Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .custom code[class*="language-"],{{WRAPPER}} .custom pre[class*="language-"]' => 'text-shadow: 0 1px {{VALUE}}',
				],
				'condition' => [
					'theme' => 'custom',
				],
			]
		);
		$this->add_control(
			'custom_slate_gray',
			[
				'label' => __( 'Slate Gray Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .custom .token.comment,{{WRAPPER}} .custom .token.prolog,{{WRAPPER}} .custom .token.doctype,{{WRAPPER}} .custom .token.cdata' => 'color: {{VALUE}}',
				],
				'condition' => [
					'theme' => 'custom',
				],
			]
		);
		$this->add_control(
			'custom_dusty_gray',
			[
				'label' => __( 'Dusty Gray Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .custom .token.punctuation' => 'color: {{VALUE}}',
				],
				'condition' => [
					'theme' => 'custom',
				],
			]
		);
		$this->add_control(
			'custom_fresh_eggplant',
			[
				'label' => __( 'Fresh Eggplant Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .custom .token.property,{{WRAPPER}} .custom .token.tag,{{WRAPPER}} .custom .token.boolean,{{WRAPPER}} .custom .token.number,{{WRAPPER}} .custom .token.constant,{{WRAPPER}} .custom .token.symbol,{{WRAPPER}} .custom .token.deleted' => 'color: {{VALUE}}',
				],
				'condition' => [
					'theme' => 'custom',
				],
			]
		);
		$this->add_control(
			'custom_limeade',
			[
				'label' => __( 'Limeade Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .custom .token.selector,{{WRAPPER}} .custom .token.attr-name,{{WRAPPER}} .custom .token.string,{{WRAPPER}} .custom .token.char,{{WRAPPER}} .custom .token.builtin,{{WRAPPER}} .custom .token.inserted' => 'color: {{VALUE}}',
				],
				'condition' => [
					'theme' => 'custom',
				],
			]
		);
		$this->add_control(
			'custom_sepia_skin',
			[
				'label' => __( 'Sepia Skin Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .custom .token.operator,{{WRAPPER}} .custom .token.entity,{{WRAPPER}} .custom .token.url,{{WRAPPER}} .custom .language-css .token.string,{{WRAPPER}} .custom .style .token.string' => 'color: {{VALUE}}',
				],
				'condition' => [
					'theme' => 'custom',
				],
			]
		);
		$this->add_control(
			'custom_xanadu',
			[
				'label' => __( 'Xanadu Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .custom .token.operator,{{WRAPPER}} .custom .token.entity,{{WRAPPER}} .custom .token.url,{{WRAPPER}} .custom .language-css .token.string,{{WRAPPER}} .custom .style .token.string' => 'background: {{VALUE}}',
				],
				'condition' => [
					'theme' => 'custom',
				],
			]
		);
		$this->add_control(
			'custom_deep_cerulean',
			[
				'label' => __( 'Deep Cerulean Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .custom .token.atrule,{{WRAPPER}} .custom .token.attr-value,{{WRAPPER}} .custom .token.keyword' => 'color: {{VALUE}}',
				],
				'condition' => [
					'theme' => 'custom',
				],
			]
		);
		$this->add_control(
			'custom_cabaret',
			[
				'label' => __( 'Cabaret Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .custom .token.function,{{WRAPPER}} .custom .token.class-name' => 'color: {{VALUE}}',
				],
				'condition' => [
					'theme' => 'custom',
				],
			]
		);
		$this->add_control(
			'custom_tangerine',
			[
				'label' => __( 'Tangerine Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .custom .token.regex,{{WRAPPER}} .custom .token.important,{{WRAPPER}} .custom .token.variable' => 'color: {{VALUE}}',
				],
				'condition' => [
					'theme' => 'custom',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
     * Register widget style controls
     */
	protected function register_style_controls() {

		$this->start_controls_section(
			'_section_source_code_style',
			[
				'label' => __('Style', 'skt-addons-elementor'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'source_code_box_height',
			[
				'label' => __('Height', 'skt-addons-elementor'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .skt-source-code pre' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'box_border',
				'label' => __('Box Border', 'skt-addons-elementor'),
				'selector' => '{{WRAPPER}}  .skt-source-code pre[class*="language-"]',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'box_border_radius',
			[
				'label' => __('Border Radius', 'skt-addons-elementor'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors' => [
					'{{WRAPPER}} .skt-source-code pre[class*="language-"]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'source_code_box_padding',
			[
				'label' => __('Padding', 'skt-addons-elementor'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors' => [
					'{{WRAPPER}} .skt-source-code pre' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]
			]
		);

		$this->add_responsive_control(
			'source_code_box_margin',
			[
				'label' => __('Margin', 'skt-addons-elementor'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors' => [
					'{{WRAPPER}} .skt-source-code pre' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]
			]
		);

		$this->add_control(
			'copy_btn_color',
			[
				'label' => __( 'Copy Button Text Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-copy-code-button' => 'color: {{VALUE}}',
				],
				'separator' => 'before',
				'condition' => [
					'copy_btn_text_show' => 'yes',
				],
			]
		);

		$this->add_control(
			'copy_btn_bg',
			[
				'label' => __( 'Copy Button Background', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-copy-code-button' => 'background-color: {{VALUE}}',
				],
				'condition' => [
					'copy_btn_text_show' => 'yes',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$source_code = $settings['source_code'];
		$theme = !empty($settings['theme']) ? $settings['theme'] : 'prism';
		$this->add_render_attribute('skt-code-wrap', 'class', 'skt-source-code');
		$this->add_render_attribute('skt-code-wrap', 'class', $theme);
		$this->add_render_attribute('skt-code-wrap', 'data-lng-type', $settings['lng_type']);
		if ('yes' == $settings['copy_btn_text_show'] && $settings['after_copy_btn_text']) {
			$this->add_render_attribute('skt-code-wrap', 'data-after-copy', $settings['after_copy_btn_text']);
		}
		$this->add_render_attribute('skt-code', 'class', 'language-' . $settings['lng_type']);
		?>
		<?php if (!empty($source_code)): ?>
			<div <?php $this->print_render_attribute_string('skt-code-wrap'); ?>>
			<pre>
			<?php if ('yes' == $settings['copy_btn_text_show'] && $settings['copy_btn_text']): ?>
				<button class="skt-copy-code-button"><?php echo esc_html($settings['copy_btn_text']) ?></button>
			<?php endif; ?>
				<code <?php $this->print_render_attribute_string('skt-code'); ?>>
					<?php echo esc_html($source_code); ?>
				</code>
			</pre>
			</div>
		<?php endif; ?>
		<?php
	}

	public function content_template() {
		?>
		<#
		var source_code = settings.source_code;
		view.addRenderAttribute( 'skt-code-wrap', 'class', 'skt-source-code');
		view.addRenderAttribute( 'skt-code-wrap', 'class', settings.theme);
		view.addRenderAttribute( 'skt-code-wrap', 'data-lng-type', settings.lng_type);
		if('yes' == settings.copy_btn_text_show && settings.after_copy_btn_text){
		view.addRenderAttribute( 'skt-code-wrap', 'data-after-copy', settings.after_copy_btn_text);
		}
		view.addRenderAttribute( 'skt-code', 'class', 'language-'+settings.lng_type);

		#>
		<# if( source_code ){ #>
		<div {{{ view.getRenderAttributeString( 'skt-code-wrap' ) }}}>
		<pre>
			<# if( 'yes' == settings.copy_btn_text_show && settings.copy_btn_text ){ #>
				<button class="skt-copy-code-button">{{{settings.copy_btn_text}}}</button>
			<# } #>
				<code {{{ view.getRenderAttributeString( 'skt-code' ) }}}>{{ source_code }}</code>
			</pre>
		</div>
		<# } #>

		<?php
	}
}