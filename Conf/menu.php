<?php
return 
[
	'menu' => 
	[
		[
			'title' => '权限管理',
			'list' => 
			[
				[
					'controller' => 'Admin',
					'icon' => 'glyphicon glyphicon-exclamation-sign',
					'title' => '管理员管理',
					'href' => '#',
					'list' => 
					[
						[
							'action' => 'index',
							'name' => '管理员列表',
							'href' => '/admin/admin/index',
						],
						[
							'action' => 'apply',
							'name' => '申请列表',
							'href' => '/admin/admin/apply'
						],
						[
							'action' => 'add',
							'name' => '添加管理员',
							'href' => '/admin/admin/add'
						]
					]
				],
				[
					'controller' => 'Role',
					'icon' => 'glyphicon glyphicon-lock',
					'title' => '角色管理',
					'href' => '#',
					'list' => 
					[
						[
							'action' => 'index',
							'name' => '角色列表',
							'href' => '/admin/role/index',
						],
						[
							'action' => 'add',
							'name' => '添加角色',
							'href' => '/admin/role/add'
						]
					]			
				],
				[
					'controller' => 'Rule',
					'icon' => 'glyphicon glyphicon-flag',
					'title' => '规则管理',
					'href' => '#',
					'list' => 
					[
						[
							'action' => 'index',
							'name' => '规则列表',
							'href' => '/admin/rule/index',
						],
						[
							'action' => 'add',
							'name' => '添加规则',
							'href' => '/admin/rule/add'
						]
					]			
				]
			]
		],
	

	]
];