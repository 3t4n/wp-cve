<?php


if (!class_exists('RD_BUTTON_CHAT_Settings')) {
    class RD_BUTTON_CHAT_Settings
    {

        public static $options;

        public function __construct()
        {
            self::$options = get_option('rd_button_chat_options');
            add_action('admin_init', array($this, 'admin_init'));
        }

        public function admin_init()
        {

            register_setting('rd_button_chat_group', 'rd_button_chat_options', array($this, 'rd_button_chat_validate'));
            register_setting('rd_button_chat_setup', 'rd_button_chat_setup_options', array($this, 'rd_button_chat_validate'));


            add_settings_section(
                'rd_button_chat_setup_section',
                esc_html__('', 'rd-button-chat'),
                null,
                'rd_button_chat_page_setup'
            );

            add_settings_field(
                'rd_button_chat_setup_email',
                esc_html__('', 'rd-button-chat'),
                array($this, 'rd_button_chat_setup_callback'),
                'rd_button_chat_page_setup',
                'rd_button_chat_setup_section',
                array(
                    'label_for' => 'rd_button_chat_setup_email',
                    'class' => 'rdwpp_setup-input'
                )
            );

            add_settings_section(
                'rd_button_chat_main_section',
                esc_html__('', 'rd-button-chat'),
                null,
                'rd_button_chat_page1'
            );

            add_settings_field(
                'rd_button_chat_show_button',
                esc_html__('Mostrar o botão de chat', 'rd-button-chat'),
                array($this, 'rd_button_chat_show_button_callback'),
                'rd_button_chat_page1',
                'rd_button_chat_main_section',
                array(
                    'label_for' => 'rd_button_chat_show_button',
                    'class' => 'rdwpp_show-button'
                )
            );


            add_settings_field(
                'rd_button_chat_phone',
                esc_html__('Telefone', 'rd-button-chat'),
                array($this, 'rd_button_chat_phone_callback'),
                'rd_button_chat_page1',
                'rd_button_chat_main_section',
                array(
                    'label_for' => 'rd_button_chat_phone',
                    'class' => 'rdwpp_tel'
                )
            );


            add_settings_field(
                'rd_button_chat_email',
                esc_html__('Email', 'rd-button-chat'),
                array($this, 'rd_button_chat_email_callback'),
                'rd_button_chat_page1',
                'rd_button_chat_main_section',
                array(
                    'label_for' => 'rd_button_chat_email',
                    'class' => 'rdwpp_email'
                )
            );


            add_settings_field(
                'rd_button_chat_message',
                esc_html__('Mensagem', 'rd-button-chat'),
                array($this, 'rd_button_chat_message_callback'),
                'rd_button_chat_page1',
                'rd_button_chat_main_section',
                array(
                    'label_for' => 'rd_button_chat_message',
                    'class' => 'rdwpp_message'
                )
            );
        }


        public function rd_button_chat_show_button_callback()
        {
?>
<input type="checkbox" name="rd_button_chat_options[rd_button_chat_show_button]" id="rd_button_chat_show_button"
    value="1"
    <?php if (isset(self::$options['rd_button_chat_show_button'])) {
                                                                                                                                            checked("1", self::$options['rd_button_chat_show_button'], true);
                                                                                                                                        } ?>>
<?php
        }

        public function rd_button_chat_phone_callback()
        {
        ?>
<input type="text" name="rd_button_chat_options[rd_button_chat_phone]" id="rd_button_chat_phone"
    placeholder="Seu número de telefone"
    value="<?php echo isset(self::$options['rd_button_chat_phone']) ? esc_attr(self::$options['rd_button_chat_phone']) : ''; ?>">
<?php
        }


        public function rd_button_chat_email_callback()
        {
        ?>
<input type="text" name="rd_button_chat_options[rd_button_chat_email]" id="rd_button_chat_email"
    placeholder="Digite seu email"
    value="<?php echo isset(self::$options['rd_button_chat_email']) && !empty(self::$options['rd_button_chat_email']) ? esc_attr(self::$options['rd_button_chat_email']) : ''; ?>">
<?php
        }


        public function rd_button_chat_message_callback()
        {
        ?>
<input type="text" name="rd_button_chat_options[rd_button_chat_message]" id="rd_button_chat_message"
    placeholder="Digite a mensagem"
    value="<?php echo isset(self::$options['rd_button_chat_message']) ? esc_attr(self::$options['rd_button_chat_message']) : ''; ?>">
<?php
        }


        public function rd_button_chat_setup_callback()
        {
        ?>
<input type="text" name="rd_button_chat_setup_options[rd_button_chat_setup_email]" id="rd_button_chat_setup_email"
    placeholder="Digite seu email aqui">
<?php
        }


        public function rd_button_chat_validate($input)
        {
            $new_input = array();
            foreach ($input as $key => $value) {
                $new_input[$key] = sanitize_text_field($value);
            }

            return $new_input;
        }
    }
}