<?php

/*
Plugin Name: [wpuprojectname] Forms
Description: Config for forms
*/

class wpuprojectid_forms {
    public function __construct() {
        /* Init */
        add_action('wp_loaded', array(&$this, 'init_forms'));

        /* Config */
        add_filter('wpucontactforms_settings', array(&$this, 'wpucontactforms_settings'));
        add_filter('wpucontactforms_submit_contactform_msg_errors', array(&$this, 'wpucontactforms_submit_contactform_msg_errors'), 10, 2);
        add_filter('wpucontactforms_display_form_after_success', '__return_false');
    }

    /* ----------------------------------------------------------
      Init
    ---------------------------------------------------------- */

    public function init_forms() {
        if (!class_exists('wpucontactforms')) {
            return;
        }

        $forms = $this->get_forms();
        foreach ($forms as $form) {
            new wpucontactforms($form);
        }
    }

    public function get_forms() {
        $forms = array();
        $forms['default_form'] = array(
            'id' => 'default_form',
            'name' => 'Default Form',
            'contact__success' => '<div>This is a custom message !</div>',
            'contact__settings' => array(
                'contact_fields' => $this->get_fields('default_form')
            )
        );
        $forms['another_form'] = array(
            'id' => 'another_form',
            'name' => 'Another Form',
            'contact__settings' => array(
                'contact_fields' => $this->get_fields('another_form')
            )
        );
        return $forms;
    }

    /* ----------------------------------------------------------
      Form values
    ---------------------------------------------------------- */

    public function get_fields($form_type = 'all') {
        $fields = array();

        $fields['contact_name'] = array(
            'autocomplete' => 'name',
            'label' => __('Name', 'wpuprojectid'),
            'required' => 1
        );
        if ($form_type == 'another_form') {
            $fields['contact_job'] = array(
                'label' => __('Job', 'wpuprojectid')
            );
        }
        $fields['contact_email'] = array(
            'label' => __('Email', 'wpuprojectid'),
            'type' => 'email',
            'required' => 1
        );
        $fields['contact_message'] = array(
            'label' => __('Message', 'wpuprojectid'),
            'type' => 'textarea',
            'required' => 1
        );

        return $fields;
    }

    public function wpucontactforms_settings($settings = array()) {
        /* Behavior */
        $settings['contact__display_form_after_success'] = false;

        /* Layout */
        $settings['group_class'] = 'cssc-form cssc-form--default';

        /* Validation config */
        $settings['label_text_required'] = '';
        $settings['enable_custom_validation'] = true;

        return $settings;
    }

    /* ----------------------------------------------------------
      Callback
    ---------------------------------------------------------- */

    /* Before submit
    -------------------------- */

    public function wpucontactforms_submit_contactform_msg_errors($errors, $form) {
        global $wpdb;

        /* Custom error */
        if ($form->options['id'] == 'default_form' && false) {
            $errors[] = __('Invalid form.', 'project');
        }

        return $errors;
    }

    /* After submit
    -------------------------- */

    public function wpucontactforms_submit_contactform($form) {

        global $wpdb;

        if ($form->options['id'] == 'default_form' && false) {
            /* CALLBACK API */
        }

    }

}

$wpuprojectid_forms = new wpuprojectid_forms();

/* ----------------------------------------------------------
  Layout helper
---------------------------------------------------------- */

/* $layouts['forms'] = wpuprojectid_forms_get_form_acf_layout(); */

function wpuprojectid_forms_get_form_acf_layout() {

    global $wpuprojectid_forms;
    $fields = array();

    /* Select a form */
    $forms = $wpuprojectid_forms->get_forms();
    $forms_choice = array();
    foreach ($forms as $id => $form) {
        $forms_choice[$id] = $form['name'];
    }
    $fields['form_type'] = array(
        'required' => 1,
        'label' => 'Form',
        'type' => 'select',
        'choices' => $forms_choice
    );

    /* Conditions */
    $condition_another = array(
        array(
            array(
                'field' => 'blockformform_type',
                'operator' => '==',
                'value' => 'another_form'
            )
        )
    );
    $fields['form_is_ok'] = array(
        'ui' => 1,
        'type' => 'true_false',
        'label' => 'A condition only for another_form',
        'conditional_logic' => $condition_another
    );

    return array(
        'key' => 'blockform',
        'label' => 'Forms',
        'sub_fields' => $fields
    );
}

/*
## Flexible content
<?php
$form_type = get_sub_field('form_type');
$form_is_ok = get_sub_field('form_is_ok');
?><section class="centered-container section cc-block--forms">
    <div class="block--forms">
    <?php do_action('wpucontactforms_content', false, $form_type);?>
    </div>
</section>
*/
