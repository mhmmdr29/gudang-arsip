<?php
/**
|--------------------------------------------------------------------------
| FORM HELPER
|--------------------------------------------------------------------------
|
| Provides functions for working with forms and form validation.
|
*/

if (!function_exists('set_value')) {
    /**
     * Form Value
     */
    function set_value($field, $default = '') {
        $CI =& get_instance();
        $value = $CI->input->post($field);

        if (is_array($field)) {
            $value = '';
            foreach ($field as $item) {
                $val = $CI->input->post($item);
                if ($val !== NULL && $val !== '') {
                    $value = $val;
                    break;
                }
            }
        }

        return $value !== NULL && $value !== '' ? $value : $default;
    }
}

if (!function_exists('set_select')) {
    /**
     * Set Select Dropdown
     */
    function set_select($field, $value, $default = '') {
        $CI =& get_instance();
        $post_value = $CI->input->post($field);

        if (is_array($field)) {
            $field = end($field);
        }

        $selected = ($post_value === '' || $post_value === NULL) ? $default : $post_value;

        if (is_array($value)) {
            foreach ($value as $k => $v) {
                $selected_attr = ($v == $selected) ? ' selected="selected"' : '';
                echo '<option value="' . $v . '"' . $selected_attr . '>' . $k . '</option>';
            }
        } else {
            $selected_attr = ($value == $selected) ? ' selected="selected"' : '';
            echo '<option value="' . $value . '"' . $selected_attr . '>' . $value . '</option>';
        }
    }
}

if (!function_exists('form_open')) {
    /**
     * Form Open Tag
     */
    function form_open($action = '', $attributes = array(), $hidden = array()) {
        $CI =& get_instance();
        $CI->load->helper('form');

        if (is_array($attributes)) {
            $atts = '';
            foreach ($attributes as $key => $val) {
                $atts .= ' ' . $key . '="' . $val . '"';
            }
            $attributes = $atts;
        }

        if (is_array($hidden)) {
            $form = '<form action="' . $action . '"' . $attributes . '>' . PHP_EOL;
            foreach ($hidden as $name => $value) {
                $form .= '<input type="hidden" name="' . $name . '" value="' . $value . '" />' . PHP_EOL;
            }
        } else {
            $form = '<form action="' . $action . '"' . $attributes . '>';
        }

        return $form;
    }
}

if (!function_exists('form_close')) {
    /**
     * Form Close Tag
     */
    function form_close($extra = '') {
        return '</form>' . $extra;
    }
}

if (!function_exists('form_input')) {
    /**
     * Form Input
     */
    function form_input($data = '', $value = '', $extra = '') {
        $CI =& get_instance();
        $defaults = array(
            'type' => 'text',
            'name' => '',
            'value' => '',
            'class' => 'form-control',
        );

        if (is_array($data)) {
            foreach ($defaults as $key => $val) {
                if (isset($data[$key])) {
                    $defaults[$key] = $data[$key];
                }
            }
        }

        if (isset($data['value'])) {
            $defaults['value'] = $data['value'];
        }

        if ($value === NULL) {
            $value = '';
        } elseif ($value !== '') {
            $defaults['value'] = $value;
        }

        return '<input ' . _parse_form_attributes($defaults, $extra) . ' />';
    }
}

if (!function_exists('form_password')) {
    /**
     * Form Password
     */
    function form_password($data = '', $value = '', $extra = '') {
        $CI =& get_instance();
        $defaults = array(
            'type' => 'password',
            'name' => '',
            'value' => '',
            'class' => 'form-control',
        );

        if (is_array($data)) {
            foreach ($defaults as $key => $val) {
                if (isset($data[$key])) {
                    $defaults[$key] = $data[$key];
                }
            }
        }

        if (isset($data['value'])) {
            $defaults['value'] = $data['value'];
        }

        if ($value === NULL) {
            $value = '';
        } elseif ($value !== '') {
            $defaults['value'] = $value;
        }

        return '<input ' . _parse_form_attributes($defaults, $extra) . ' />';
    }
}

if (!function_exists('form_dropdown')) {
    /**
     * Form Dropdown
     */
    function form_dropdown($name = '', $options = array(), $selected = array(), $extra = '') {
        $CI =& get_instance();
        $defaults = array(
            'name' => $name,
            'options' => $options,
            'selected' => $selected,
            'class' => 'form-select',
        );

        if (is_array($name)) {
            foreach ($defaults as $key => $val) {
                if (isset($name[$key])) {
                    $defaults[$key] = $name[$key];
                }
            }
        }

        if (is_array($options)) {
            foreach ($options as $key => $val) {
                $defaults['options'][$key] = $val;
            }
        }

        if (is_array($selected)) {
            foreach ($selected as $key => $val) {
                if (isset($selected[$key])) {
                    $defaults['selected'][$key] = $val;
                }
            }
        }

        $CI->load->helper('form');
        return $CI->form->dropdown($defaults);
    }
}

if (!function_exists('form_textarea')) {
    /**
     * Form Textarea
     */
    function form_textarea($data = '', $value = '', $extra = '') {
        $CI =& get_instance();
        $defaults = array(
            'name' => '',
            'value' => $value,
            'rows' => '10',
            'class' => 'form-control',
        );

        if (is_array($data)) {
            foreach ($defaults as $key => $val) {
                if (isset($data[$key])) {
                    $defaults[$key] = $data[$key];
                }
            }
        }

        return '<textarea ' . _parse_form_attributes($defaults, $extra) . '>' . $value . '</textarea>';
    }
}

if (!function_exists('form_submit')) {
    /**
     * Form Submit
     */
    function form_submit($data = '', $content = '', $extra = '') {
        $CI =& get_instance();
        $defaults = array(
            'type' => 'submit',
            'name' => 'submit',
            'value' => 'Submit',
            'class' => 'btn btn-primary',
        );

        if (is_array($data)) {
            foreach ($defaults as $key => $val) {
                if (isset($data[$key])) {
                    $defaults[$key] = $data[$key];
                }
            }
        }

        if (isset($data['value'])) {
            $defaults['value'] = $data['value'];
        }

        if ($content !== '') {
            $defaults['content'] = $content;
        }

        return '<input ' . _parse_form_attributes($defaults, $extra) . ' />';
    }
}

/**
 * Parse Form Attributes
 */
function _parse_form_attributes($attributes, $default) {
    if (is_array($default)) {
        foreach ($default as $key => $val) {
            if (isset($attributes[$key])) {
                $attributes[$key] = $val;
            }
        }
    }

    $atts = '';
    foreach ($attributes as $key => $val) {
        $atts .= ' ' . $key . '="' . $val . '"';
    }

    return $atts;
}
