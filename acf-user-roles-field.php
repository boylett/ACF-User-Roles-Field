<?php

	/**
   * Plugin Name:  Advanced Custom Fields - User Roles Field
   * Plugin URI:   https://github.com/boylett/ACF-User-Roles-Field
   * Description:  Adds an ACF field for User Role selection
   * Author:       Ryan Boylett
   * Author URI:   https://github.com/boylett
	 */

	add_action('acf/include_field_types', function()
	{
		class ACF_Field_User_Roles extends acf_field
		{
			public function __construct()
			{
				$this->name     = 'user_role';
				$this->label    = 'User Role';
				$this->category = 'relational';
				$this->defaults = array
				(
					'allow_null' 	 => 0,
					'allow_multiple' => 0,
					'return_format'	 => 'name'
				);

				parent::__construct();
			}

			public function render_field($field)
			{
				$counts = count_users();
				$roles  = get_editable_roles(); ?>

				<select id="<?=esc_attr($field['id'])?>" class="<?=esc_attr($field['class'])?>" name="<?=esc_attr($field['name'])?>"<?=((isset($field['allow_multiple']) and $field['allow_multiple']) ? ' multiple' : '')?>><?
					if(isset($field['allow_null']) and $field['allow_null'])
					{ ?>

					<option value="">Select Role</option><?
					}

					if(!empty($roles))
					{
						foreach($roles as $role_name => $role)
						{ ?>

					<option value="<?=esc_attr($role_name)?>" <? selected($field['value'], $role_name); ?>><?=esc_html((isset($role['name']) ? $role['name'] : $role_name) . ' (' . number_format(isset($counts['avail_roles'][$role_name]) ? $counts['avail_roles'][$role_name] : 0) . ')')?></option><?
						}
					} ?>

				</select><?
			}

			function render_field_settings($field)
			{
				acf_render_field_setting($field, array
				(
					'label'			=> __('Allow Null?','acf'),
					'instructions'	=> '',
					'name'			=> 'allow_null',
					'type'			=> 'true_false',
					'ui'			=> 1,
				));

				acf_render_field_setting($field, array
				(
					'label'			=> __('Select multiple values?','acf'),
					'instructions'	=> '',
					'name'			=> 'allow_multiple',
					'type'			=> 'true_false',
					'ui'			=> 1,
				));

				acf_render_field_setting($field, array
				(
					'label'			=> __('Return Format','acf'),
					'instructions'	=> '',
					'type'			=> 'radio',
					'name'			=> 'return_format',
					'choices'		=> array
					(
						'object'		=> 'Role Object',
						'name'			=> 'Role Name'
					),
					'layout'	=>	'horizontal'
				));
			}

			function format_value($value, $post_id, $field)
			{
				if(empty($value))
				{
					return false;
				}

				if($field['return_format'] == 'object')
				{
					return $value;
				}

				return $value;
			}
		}

		new ACF_Field_User_Roles();
	});
