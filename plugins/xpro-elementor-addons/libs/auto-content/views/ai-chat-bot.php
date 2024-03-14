<?php
$settings        = get_option( 'xpro_assistant_settings' );
$temperature     = isset( $settings['temperature'] ) ? $settings['temperature'] : '0.7';
$tokens          = isset( $settings['tokens'] ) ? $settings['tokens'] : '2048';
$randomness      = isset( $settings['randomness'] ) ? $settings['randomness'] : '1';
$frequency       = isset( $settings['frequency'] ) ? $settings['frequency'] : '0';
$presence        = isset( $settings['presence'] ) ? $settings['presence'] : '0';
$output_language = isset( $settings['output_language'] ) ? $settings['output_language'] : 'en';
$speak_language  = isset( $settings['speak_language'] ) ? $settings['speak_language'] : 'Google US English';
$speak_rate      = isset( $settings['speak_rate'] ) ? $settings['speak_rate'] : '1';
$speak_pitch     = isset( $settings['speak_pitch'] ) ? $settings['speak_pitch'] : '0';
?>
<div class="xpro-assistant-wrapper">
	<form id="xpro-assistant-form">
		<div id="xpro-assistant-chat-wrapper">
			<div id="xpro-assistant-chat-container">
				<div class="xpro-assistant-chat-item ai">
					<div class="xpro-assistant-chat">
						<div class="xpro-assistant-profile">
							<img src="<?php echo esc_url( XPRO_ELEMENTOR_ADDONS_ASSETS . 'admin/images/xpro-assistant.png' ); ?>" alt="bot-image">
						</div>
						<div class="xpro-assistant-message"><?php echo esc_html__( 'Hey, what can I do for you today?', 'xpro-elementor-addons' ); ?></div>
					</div>
				</div>
			</div>
			<div class="xpro-assistant-form-submit-wrapper">
				<input required name="prompt" placeholder="<?php echo esc_html__( 'Ask me...', 'xpro-elementor-addons' ); ?>"/>
				<button class="xpro-assistant-voice-btn" type="button">
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 50 70">
						<path d="M39.96 25.008v9.828c-.026 8.652-6.905 15.332-15.546 15.125-7.7-.2-14.305-6.746-14.398-14.453a860.09 860.09 0 0 1 0-21.059C10.106 7.277 15.922.99 23.05.168c9.18-1.063 16.887 5.781 16.91 15.02.008 3.265 0 6.542 0 9.82Zm-24.968-.047c0 3.277-.015 6.555 0 9.828 0 .781.078 1.559.235 2.32 1.109 4.961 5.828 8.383 10.714 7.817 5.2-.602 9-4.785 9.016-9.961.023-6.66.023-13.32 0-19.977 0-.726-.082-1.453-.242-2.16-1.133-4.969-5.836-8.36-10.73-7.777-5.204.625-8.965 4.808-8.981 10-.024 3.304-.024 6.61-.024 9.91Zm0 0"/>
						<path d="M22.45 64.941v-4.898c-2.188-.602-4.391-.977-6.407-1.805C6.711 54.387 1.371 47.398.148 37.355c-.207-1.617-.14-3.27-.132-4.906a2.497 2.497 0 0 1 4.977.031c0 2.497 0 4.997.624 7.438 2.367 9.477 11.653 15.98 21.356 14.93 10.336-1.125 17.945-9.559 17.992-19.953 0-.856-.031-1.715.015-2.575a2.496 2.496 0 0 1 4.961-.011c.336 6.347-.972 12.257-4.84 17.43-4.386 5.87-10.265 9.19-17.574 10.116v5.094c1.664 0 3.328-.02 4.98 0a2.46 2.46 0 0 1 2.36 1.82 2.38 2.38 0 0 1-1.02 2.72 2.856 2.856 0 0 1-1.398.433c-4.992.035-9.988.039-14.98.016a2.484 2.484 0 0 1-2.489-2.461v-.016a2.481 2.481 0 0 1 2.54-2.5c1.625-.035 3.234-.02 4.93-.02Zm0 0"/>
					</svg>
				</button>
				<button class="xpro-assistant-submit-btn" type="submit">
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 50 50">
						<path d="M17.809 27.414h11.906c1.273 0 2.183-.648 2.508-1.75a2.402 2.402 0 0 0-2.18-3.066c-.23-.016-.461-.008-.688-.008-7.824 0-15.648 0-23.468.008-.543 0-.723-.114-.867-.7-.457-1.87-1.043-3.71-1.598-5.558C2.402 12.934 1.375 9.527.34 6.125.047 5.156-.105 4.191.082 3.211.527.867 2.586-.406 5.032.164a9.054 9.054 0 0 1 1.917.711c13.164 6.516 26.32 13.047 39.469 19.586a9.009 9.009 0 0 1 2.25 1.527c1.785 1.727 1.773 4.293-.012 6.024a8.849 8.849 0 0 1-2.14 1.472A9958.41 9958.41 0 0 1 17.582 43.86c-3.617 1.797-7.234 3.59-10.844 5.38-1.07.53-2.195.863-3.418.683-1.855-.277-3.215-1.684-3.265-3.543a8.275 8.275 0 0 1 .316-2.512c1.442-4.918 2.945-9.82 4.418-14.73.137-.39.23-.797.277-1.211.032-.434.215-.52.614-.516 4.039.012 8.086.012 12.129.004Zm0 0"/>
					</svg>
				</button>
			</div>
		</div>
		<div class="xpro-assistant-setting-wrapper">
			<ul class="xpro-setting-list">
				<li>
					<label for="xpro-assistant-temperature">
						<?php echo esc_html__( 'Temperature', 'xpro-elementor-addons' ); ?>
						<span class="xpro-assistant-tooltip">
							<?php echo esc_html__( 'Higher values means less accurate but varied and creative output. Lower values will produce more accurate and realistic results.', 'xpro-elementor-addons' ); ?>
						</span>
					</label>
					<input type="number" id="xpro-assistant-temperature" step="0.1" name="xpro-assistant-temperature" value="<?php echo esc_attr( $temperature ); ?>">
				</li>
				<li>
					<label for="xpro-assistant-tokens">
						<?php echo esc_html__( 'Max Tokens', 'xpro-elementor-addons' ); ?>
						<span class="xpro-assistant-tooltip">
							<?php echo esc_html__( 'The maximum number of tokens to generate in the completion.', 'xpro-elementor-addons' ); ?>
						</span>
					</label>
					<input type="number" id="xpro-assistant-tokens" step="1" name="xpro-assistant-tokens" value="<?php echo esc_attr( $tokens ); ?>">
				</li>
				<li>
					<label for="xpro-assistant-randomness">
						<?php echo esc_html__( 'Randomness', 'xpro-elementor-addons' ); ?>
						<span class="xpro-assistant-tooltip">
							<?php echo esc_html__( 'To control randomness of the output.', 'xpro-elementor-addons' ); ?>
						</span>
					</label>
					<input type="number" id="xpro-assistant-randomness" step="0.1" name="xpro-assistant-randomness" value="<?php echo esc_attr( $randomness ); ?>">
				</li>
				<li>
					<label for="xpro-assistant-frequency">
						<?php echo esc_html__( 'Frequency Penalty', 'xpro-elementor-addons' ); ?>
						<span class="xpro-assistant-tooltip">
							<?php echo esc_html__( 'Use with "Reality and creativity" to test the authenticity and randomness of the result.', 'xpro-elementor-addons' ); ?>
						</span>
					</label>
					<input type="number" id="xpro-assistant-frequency" step="0.1" name="xpro-assistant-frequency" value="<?php echo esc_attr( $frequency ); ?>">
				</li>
				<li>
					<label for="xpro-assistant-presence">
						<?php echo esc_html__( 'Presence Penalty', 'xpro-elementor-addons' ); ?>
						<span class="xpro-assistant-tooltip">
							<?php echo esc_html__( 'For improving the quality and coherence of the generated text.', 'xpro-elementor-addons' ); ?>
						</span>
					</label>
					<input type="number" id="xpro-assistant-presence" step="0.1" name="xpro-assistant-presence" value="<?php echo esc_attr( $presence ); ?>">
				</li>
				<li>
					<label for="xpro-assistant-language">
						<?php echo esc_html__( 'Output Language', 'xpro-elementor-addons' ); ?>
						<span class="xpro-assistant-tooltip">
							<?php echo esc_html__( 'The texts will be written in the chosen language.', 'xpro-elementor-addons' ); ?>
						</span>
					</label>
					<select id="xpro-assistant-language" name="xpro-assistant-language" value="<?php echo esc_attr( $output_language ); ?>" data-value="<?php echo esc_attr( $output_language ); ?>">
						<option value="en"><?php echo esc_html__( 'English', 'xpro-elementor-addons' ); ?></option>
						<option value="en-GB"><?php echo esc_html__( 'English UK', 'xpro-elementor-addons' ); ?></option>
						<option value="fr"><?php echo esc_html__( 'French', 'xpro-elementor-addons' ); ?></option>
						<option value="de"><?php echo esc_html__( 'German', 'xpro-elementor-addons' ); ?></option>
						<option value="es"><?php echo esc_html__( 'Spanish', 'xpro-elementor-addons' ); ?></option>
						<option value="it"><?php echo esc_html__( 'Italian', 'xpro-elementor-addons' ); ?></option>
						<option value="nl"><?php echo esc_html__( 'Dutch', 'xpro-elementor-addons' ); ?></option>
						<option value="pt-BR"><?php echo esc_html__( 'Portuguese', 'xpro-elementor-addons' ); ?></option>
						<option value="pl"><?php echo esc_html__( 'Polish', 'xpro-elementor-addons' ); ?></option>
						<option value="ru"><?php echo esc_html__( 'Russian', 'xpro-elementor-addons' ); ?></option>
						<option value="ja"><?php echo esc_html__( 'Japanese', 'xpro-elementor-addons' ); ?></option>
						<option value="zh-CN"><?php echo esc_html__( 'Chinese', 'xpro-elementor-addons' ); ?></option>
						<option value="tr"><?php echo esc_html__( 'Turkish', 'xpro-elementor-addons' ); ?></option>
						<option value="ar"><?php echo esc_html__( 'Arabic', 'xpro-elementor-addons' ); ?></option>
						<option value="ko"><?php echo esc_html__( 'Korean', 'xpro-elementor-addons' ); ?></option>
						<option value="id"><?php echo esc_html__( 'Indonesian', 'xpro-elementor-addons' ); ?></option>
						<option value="sv"><?php echo esc_html__( 'Swedish', 'xpro-elementor-addons' ); ?></option>
						<option value="da"><?php echo esc_html__( 'Danish', 'xpro-elementor-addons' ); ?></option>
						<option value="fi"><?php echo esc_html__( 'Finnish', 'xpro-elementor-addons' ); ?></option>
						<option value="no"><?php echo esc_html__( 'Norwegian', 'xpro-elementor-addons' ); ?></option>
						<option value="ro"><?php echo esc_html__( 'Romanian', 'xpro-elementor-addons' ); ?></option>
					</select>
				</li>
				<li>
					<label for="xpro-assistant-speak">
						<?php echo esc_html__( 'Speak Language', 'xpro-elementor-addons' ); ?>
					</label>
					<select name="xpro-assistant-speak" id="xpro-assistant-speak" value="<?php echo esc_attr( $speak_language ); ?>"></select>
				</li>
				<li>
					<label for="xpro-assistant-speak-rate">
						<?php echo esc_html__( 'Speak Rate', 'xpro-elementor-addons' ); ?>
						<span class="xpro-assistant-tooltip">
								<?php echo esc_html__( 'Rate only work with native voice.', 'xpro-elementor-addons' ); ?>
							</span>
					</label>
					<input type="number" id="xpro-assistant-speak-rate" step="0.1" name="xpro-assistant-speak-rate" value="<?php echo esc_attr( $speak_rate ); ?>">
				</li>
				<li>
					<label for="xpro-assistant-speak-pitch">
						<?php echo esc_html__( 'Speak Pitch', 'xpro-elementor-addons' ); ?>
						<span class="xpro-assistant-tooltip">
								<?php echo esc_html__( 'Pitch only work with native voice.', 'xpro-elementor-addons' ); ?>
							</span>
					</label>
					<input type="number" id="xpro-assistant-speak-pitch" step="0.1" name="xpro-assistant-speak-pitch" value="<?php echo esc_attr( $speak_pitch ); ?>">
				</li>
			</ul>
			<button class="xpro-assistant-save-settings" type="button">
				<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.922 0c1.613.008 3.058.32 4.418.996.277.14.398.399.316.668-.105.36-.441.48-.816.34-.621-.23-1.238-.496-1.88-.64C7.099.25 2.27 3.41 1.317 8.303.395 13.032 3.45 17.653 8.18 18.68c4.851 1.054 9.68-2.2 10.535-7.106a9.224 9.224 0 0 0-.23-4.18c-.106-.37.019-.664.316-.777a.553.553 0 0 1 .465.02c.144.078.246.21.28.37.145.59.302 1.184.372 1.79.316 2.691-.371 5.129-2.008 7.27-1.664 2.183-3.887 3.488-6.617 3.851-5.215.684-10.02-2.77-11.078-7.926A9.992 9.992 0 0 1 6.109.785 9.85 9.85 0 0 1 9.922 0Zm0 0"/></svg>
				<?php echo esc_html__( 'Save Settings' ); ?>
			</button>
			<button class="xpro-assistant-settings-toggle" type="button">
				<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 50 50"><path d="M19.922 0h10.156c.77.332 1.11.914 1.125 1.758.016.91.113 1.816.16 2.726.016.29.098.45.403.567.882.328 1.746.703 2.609 1.082.223.097.367.094.555-.078.746-.676 1.511-1.332 2.273-1.996.781-.684 1.543-.668 2.285.07 2.133 2.125 4.258 4.254 6.383 6.383.738.742.754 1.504.074 2.289-.664.761-1.34 1.508-1.98 2.285a.63.63 0 0 0-.078.535c.34.875.73 1.73 1.062 2.61.114.3.281.39.57.398.915.047 1.817.148 2.727.164.844.012 1.422.36 1.758 1.121V30.07c-.336.766-.914 1.11-1.758 1.125-.914.012-1.82.114-2.73.16-.29.016-.453.102-.567.403-.332.883-.726 1.738-1.062 2.617a.635.635 0 0 0 .078.535c.644.774 1.32 1.524 1.98 2.285.664.762.664 1.54-.043 2.254a1436.814 1436.814 0 0 1-6.457 6.453c-.695.692-1.468.7-2.218.055-.762-.66-1.524-1.32-2.274-1.996a.467.467 0 0 0-.594-.098c-.859.383-1.726.75-2.609 1.086a.512.512 0 0 0-.39.52 161.947 161.947 0 0 1-.204 3.07c-.066.887-.633 1.445-1.52 1.445-3.09.012-6.179.012-9.273 0-.89 0-1.468-.55-1.535-1.433-.074-1.024-.148-2.043-.2-3.067-.015-.304-.136-.449-.421-.558a53.684 53.684 0 0 1-2.562-1.063c-.247-.11-.391-.101-.594.082-.746.68-1.512 1.34-2.274 1.996-.761.66-1.53.645-2.25-.07a1652.88 1652.88 0 0 1-6.41-6.41c-.719-.719-.734-1.484-.07-2.246.664-.766 1.32-1.528 2-2.274.183-.207.183-.355.074-.597A52.714 52.714 0 0 1 5.06 31.78c-.11-.285-.25-.402-.555-.418-1.008-.05-2.016-.125-3.02-.199C.54 31.094 0 30.527 0 29.578v-9.176c0-.93.54-1.5 1.45-1.566a202.43 202.43 0 0 1 3.07-.203.516.516 0 0 0 .52-.39c.339-.88.698-1.75 1.085-2.61.113-.262.074-.399-.102-.594-.664-.734-1.312-1.488-1.957-2.234-.687-.785-.675-1.547.063-2.285 2.125-2.133 4.254-4.262 6.387-6.383.742-.742 1.496-.766 2.285-.082.761.66 1.508 1.34 2.285 1.984a.638.638 0 0 0 .531.078c.88-.34 1.738-.73 2.617-1.062.301-.114.391-.278.403-.57.047-.915.148-1.817.164-2.727.012-.844.355-1.422 1.12-1.758Zm27.125 21.656-2.938-.187c-.914-.059-1.304-.438-1.625-1.282a68.014 68.014 0 0 0-1.703-4.132c-.394-.856-.394-1.352.215-2.051.652-.746 1.305-1.492 1.914-2.195l-4.726-4.715c-.715.625-1.461 1.277-2.211 1.926-.68.585-1.188.59-2.012.207-1.293-.594-2.61-1.172-3.941-1.657-1.141-.41-1.438-.707-1.504-1.902-.047-.902-.121-1.805-.184-2.715h-6.668c-.062.945-.129 1.867-.18 2.79-.058 1.077-.39 1.433-1.382 1.796a61.793 61.793 0 0 0-4.047 1.684c-.84.39-1.36.39-2.051-.22a620.98 620.98 0 0 0-2.191-1.91l-4.715 4.723c.597.692 1.234 1.41 1.863 2.141.668.77.66 1.246.226 2.176a48.642 48.642 0 0 0-1.628 3.902c-.395 1.074-.707 1.387-1.844 1.453-.91.051-1.836.121-2.758.184v6.668l2.938.191c.91.059 1.304.438 1.625 1.282a65.597 65.597 0 0 0 1.703 4.132c.386.848.394 1.356-.22 2.055-.651.746-1.304 1.488-1.905 2.188l4.722 4.718c.692-.601 1.414-1.23 2.137-1.863.758-.66 1.23-.66 2.129-.246a52.927 52.927 0 0 0 3.95 1.648c1.089.399 1.39.715 1.448 1.89.047.907.121 1.806.184 2.716h6.668c.066-.996.133-1.969.191-2.942.059-.894.41-1.37 1.239-1.593a18.41 18.41 0 0 0 4.226-1.746c.758-.426 1.313-.368 1.965.199.762.652 1.516 1.32 2.227 1.941l4.722-4.722c-.613-.704-1.258-1.438-1.894-2.176-.64-.742-.63-1.203-.219-2.086.594-1.281 1.156-2.578 1.633-3.903.422-1.164.715-1.445 1.957-1.503.89-.043 1.773-.122 2.668-.18Zm0 0"/><path d="M35.875 25.04c-.02 6-4.898 10.843-10.898 10.827-6.032-.027-10.887-4.93-10.836-10.937.05-6.004 4.933-10.852 10.921-10.805 6 .027 10.844 4.914 10.813 10.914Zm-10.883-7.981a7.935 7.935 0 0 0-7.922 7.957 7.94 7.94 0 1 0 15.88-.032 7.92 7.92 0 0 0-2.337-5.617 7.91 7.91 0 0 0-5.629-2.308Zm0 0"/></svg>
			</button>
		</div>
	</form>
</div>
