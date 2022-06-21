<?php

$ciscoLayer3 = [
  'set_interface_vlan_layer3_append' => [
    'interface vlan :vlan',
    'no shutdown',
    'ip dhcp relay address :dhcp_ip',
    'exit',
  ],
  'set_interface_vlan_layer3_ipv4_append_primary' => [
    'interface vlan :vlan',
    'ip address :ipv4_gateway/:ipv4_cidr_mask',
    'exit',
  ],
  'set_interface_vlan_layer3_ipv4_append_secondary' => [
    'ip route :ipv4_gateway/:ipv4_cidr_mask vlan :vlan',
  ],
  'set_interface_vlan_layer3_ipv6_append' => [
    'interface vlan :vlan',
    'ipv6 address :ipv6_gateway/:ipv6_cidr_mask',
    'exit',
  ],
  'set_interface_vlan_layer3_ipv6_append_secondary' => [
    'ipv6 route :ipv6_gateway/:ipv6_cidr_mask vlan :vlan',
  ],

  'interface_wipe_layer3_ipv4_append' => [
    'interface vlan :vlan',
    'no ip address :ipv4_gateway/:ipv4_cidr_mask',
    'exit',
  ],
  'interface_wipe_layer3_ipv4_append_secondary' => [
    'interface vlan :vlan',
    'no ip address :ipv4_gateway/:ipv4_cidr_mask secondary',
    'exit',
  ],
  'interface_wipe_layer3_ipv6_append' => [
    'interface vlan :vlan',
    'no ipv6 address :ipv6_gateway/:ipv6_cidr_mask',
    'exit',
  ],
  'interface_wipe_layer3_ipv6_append_secondary' => [
    'interface vlan :vlan',
    'no ipv6 address :ipv6_gateway/:ipv6_cidr_mask secondary',
    'exit',
  ],
];

$ciscoDist = [
  'dist' => [
    'enable' => 'en',
    'config' => 'config t',
    'set_interface_vlan' => [
      'ip route :ipv4_gateway/:ipv4_cidr_mask :ipv4_rack_switch_address',
    ],
    'set_interface_vlan_ipv6_append' => [
      'ipv6 route :ipv6_subnet/:ipv6_cidr_mask :ipv6_rack_switch_address',
    ],
    'interface_wipe' => [
      'no ip route :ipv4_gateway/:ipv4_cidr_mask :ipv4_rack_switch_address',
    ],
    'interface_wipe_ipv6_append' => [
      'no ipv6 route :ipv6_subnet/:ipv6_cidr_mask :ipv6_rack_switch_address',
    ],
    'exit_config' => ['exit', 'copy running-config startup-config'],
    'exit_enable' => 'exit',
  ],
];

$dellDefault = [
  'name' => 'Dell Networking',
  'enable' => 'en',
  'config' => 'config t',
  'set_interface_vlan' => [
    'vlan :vlan',
    'interface :port',
    'switchport access vlan :vlan',
    'exit',
  ],
  'set_interface_port_on' => ['interface :port', 'no shutdown', 'exit'],
  'set_interface_port_off' => ($dell_port_off = [
    'interface :port',
    'shutdown',
    'exit',
  ]),
  'set_interface_port_speed' => [
    'interface :port',
    'speed auto :speed',
    'exit',
  ],

  'interface_wipe' => $dell_port_off,

  'exit_config' => 'exit',
  'exit_enable' => 'exit',
];

$ciscoDefault =
  [
    'exit_config' => ['exit', 'copy running-config startup-config'],
  ] + $dellDefault;

return [
  'invalid_input' => 'The switch did not understand our command.',
  'auth_fail' => 'Incorrect Switch SSH username or password',
  'missing_entity' => 'This server does not have an IP Entity assigned.',

  'dell' => [
    'networking' => $dellDefault,
    '6248' => [
      'name' => 'Dell PowerConnect',
      'enable' => 'en',
      'config' => 'configure',
      'set_interface_vlan' => [
        'interface ethernet :port',
        'switchport access vlan :vlan',
        'exit',
      ],
      'set_interface_port_on' => [
        'interface ethernet :port',
        'no shutdown',
        'exit',
      ],
      'set_interface_port_off' => ($dell_6248_port_off = [
        'interface ethernet :port',
        'shutdown',
        'exit',
      ]),
      'set_interface_port_speed' => [
        'interface ethernet :port',
        'negotiation :speedf',
        'exit',
      ],
      'interface_wipe' => $dell_6248_port_off,
      'exit_config' => 'exit',
      'exit_enable' => 'exit',
    ],
  ],
  'supermicro' =>
    [
      'name' => 'Supermicro',
      'smis' => [
        'name' => 'Supermicro SMIS',
        'enable' => 'en',
        'config' => 'config t',
        'set_interface_vlan' => [
          'vlan :vlan',
          'exit',
          'interface :port',
          'switchport access vlan :vlan',
          'exit',
        ],
        'set_interface_port_on' => ['interface :port', 'no shutdown', 'exit'],
        'set_interface_port_off' => ($sm_smis_port_off = [
          'interface :port',
          'shutdown',
          'exit',
        ]),
        'set_interface_port_speed' => [
          'interface :port',
          'no negotiation',
          'speed :speed auto',
          'exit',
        ],
        'interface_wipe' => $sm_smis_port_off,
        'exit_config' => 'exit',
        'exit_enable' => 'exit',
      ],
    ] +
    $ciscoLayer3 +
    $ciscoDist +
    $dellDefault,
  'juniper' => [
    'legacy' => ($juniper = [
      'name' => 'Juniper (Legacy)',
      'enable' => 'cli',
      'config' => 'configure private',
      'set_interface_vlan' => [
        'delete interfaces :port unit 0 family ethernet-switching vlan members',
        'set interfaces :port unit 0 family ethernet-switching port-mode access',
        'set vlans vlan.:vlan vlan-id :vlan',
        'set interfaces :port unit 0 family ethernet-switching vlan members :vlan',
      ],
      'set_interface_port_on' => ['delete interfaces :port disable'],
      'set_interface_port_off' => ($juniper_port_off = [
        'set interfaces :port disable',
      ]),
      'set_interface_port_speed' => [
        'set interfaces :port ether-options speed :speed',
      ],
      'interface_wipe' => $juniper_port_off,

      // Layer 3
      'set_interface_vlan_layer3_append' => [
        'set vlans vlan.:vlan l3-interface vlan.:vlan',
      ],
      'set_interface_vlan_layer3_ipv4_append' => [
        'set interfaces vlan unit :vlan family inet address :ipv4_gateway/:ipv4_cidr_mask',
      ],
      'set_interface_vlan_layer3_ipv6_append' => [
        'set interfaces vlan unit :vlan family inet6 address :ipv6_gateway/:ipv6_cidr_mask',
      ],
      'interface_wipe_layer3_ipv4_append' => [
        'delete interfaces vlan unit :vlan family inet address :ipv4_gateway/:ipv4_cidr_mask',
      ],
      'interface_wipe_layer3_ipv6_append' => [
        'delete interfaces vlan unit :vlan family inet6 address :ipv6_gateway/:ipv6_cidr_mask',
      ],

      'exit_config' => ['commit', 'exit'],
      'exit_enable' => 'exit',
    ]),
    'arp-locking' => [
      'name' => 'Juniper (ARP Locking via dhcp-options)',
      'enable' => 'cli',
      'config' => 'configure private',
      'assign_individual_address_v4' => [
        'set vlans default forwarding-options dhcp-security group filter interface :port static-ip :usable_ip mac :mac_address',
      ],
      'assign_individual_address_v6' => [
        'set vlans default forwarding-options dhcp-security group filter interface :port static-ipv6 :usable_ip mac :mac_address',
      ],
      'unassign_individual_addresses' => [
        'delete vlans default forwarding-options dhcp-security group filter interface :port',
        'delete vlans default forwarding-options dhcp-security group trusted interface :port',
      ],
      'set_interface_port_on' => ['delete interfaces :port disable'],
      'set_interface_port_off' => ($juniper_port_off = [
        'set interfaces :port disable',
      ]),
      'set_interface_port_speed' => [
        'set interfaces :port ether-options speed :speed',
      ],
      'interface_wipe' => $juniper_port_off,

      'exit_config' => ['commit', 'exit'],
      'exit_enable' => 'exit',
    ],
    'els' =>
      [
        'name' => 'Juniper (ELS)',
        'set_interface_vlan' => [
          'delete interfaces :port unit 0 family ethernet-switching vlan members',
          'set interfaces :port unit 0 family ethernet-switching interface-mode access',
          'set vlans vlan.:vlan vlan-id :vlan',
          'set interfaces :port unit 0 family ethernet-switching vlan members :vlan',
        ],
        'set_interface_port_speed' => ['set interfaces :port speed :speed'],
      ] + $juniper,
  ],
  'brocade' => [
    'name' => 'Brocade',
    'enable' => 'enable',
    'config' => 'config t',
    'set_interface_vlan' => [
      'vlan :vlan',
      'untagged eth :port',
      # Exit VLAN config
      'exit',
    ],
    'set_interface_port_on' => [
      //'portcfgpersistentenable :port',
      ($brocade_noop =
        '# This is a no-op on brocade switches due to lack of support in the default model.'),
    ],
    'set_interface_port_off' => ($brocade_port_off = [
      //'portcfgpersistentdisable :port',
      $brocade_noop,
    ]),
    'set_interface_port_speed' => [
      # NOTE: Brocade has weird settings for port speed (1gbps-16gbps) - so we just auto negotiate.
      //'portcfgspeed 0',
      $brocade_noop,
    ],
    'interface_wipe' => [
      'vlan :vlan',
      'no untagged eth :port',
      # Exit VLAN config
      'exit',
    ],
    'exit_config' => ($brocade_save_and_exit = ['wr me', 'exit']),
    'exit_enable' => 'exit',
  ],
  'brocade-fcx' => [
    'name' => 'Brocade FCX/ICX',
    'enable' => 'enable',
    'config' => 'config t',
    'set_interface_vlan' => [
      'vlan :vlan',
      'untagged eth :port',
      # Exit VLAN config
      'exit',
    ],
    'set_interface_port_on' => ['interface eth :port', 'no disable'],
    'set_interface_port_off' => ['interface eth :port', 'disable'],
    'set_interface_port_speed' => [
      'interface eth :port',
      'speed-duplex :speed',
    ],
    'interface_wipe' => [
      # Turn off the port power
      'interface eth :port',
      'disable',

      # Disable the VLAN association
      'vlan :vlan',
      'no untagged eth :port',
      'exit',
    ],
    'exit_config' => $brocade_save_and_exit,
    'exit_enable' => 'exit',
  ],
  'arista' => [
    'eos' => [
      'name' => 'Arista EOS',
      'enable' => 'en',
      'config' => 'conf',
      'set_interface_vlan' => [
        'interface ethernet :port',
        'switchport access vlan :vlan',
        'exit',
      ],
      'set_interface_port_on' => [
        'interface ethernet :port',
        'no shutdown',
        'exit',
      ],
      'set_interface_port_off' => [
        'interface ethernet :port',
        'shutdown',
        'exit',
      ],
      'set_interface_port_speed' => [
        'interface ethernet :port',
        'speed forced :speedfull',
        'exit',
      ],
      'interface_wipe' => [
        'interface ethernet :port',
        'no switchport access vlan :vlan',
        'shutdown',
        'exit',
      ],
      'exit_config' => 'no command',
      'exit_enable' => 'exit',
    ],
    'eos_v4' => [
      'name' => 'Arista EOS v4',
      'enable' => 'en',
      'config' => 'conf',
      'set_interface_vlan' => [
        'interface :port',
        'switchport access vlan :vlan',
        'exit',
      ],
      'set_interface_port_on' => ['interface :port', 'no shutdown', 'exit'],
      'set_interface_port_off' => ['interface :port', 'shutdown', 'exit'],
      'set_interface_port_speed' => [
        'interface :port',
        'speed forced :speedfull',
        'exit',
      ],
      'interface_wipe' => [
        'interface :port',
        'no switchport access vlan :vlan',
        'shutdown',
        'exit',
      ],
      'exit_config' => 'no command',
      'exit_enable' => 'exit',
    ],
  ],
  'fs' => [
    'v1' => [
      'name' => 'FS',
      'enable' => 'en',
      'config' => 'configure terminal',
      'set_interface_vlan' => [
        'vlan :vlan',
        'exit',
        'interface :port',
        'switchport mode access',
        'switchport access vlan :vlan',
        'exit',
      ],
      'set_interface_port_on' => ['interface :port', 'no shutdown', 'exit'],
      'set_interface_port_off' => ['interface :port', 'shutdown', 'exit'],
      'set_interface_port_speed' => ['interface :port', 'speed :speed', 'exit'],

      'set_interface_vlan_layer3_append' => [
        'interface vlan:vlan',
        'dhcp relay information trusted',
        'dhcp-server 1',
        'exit',
      ],
      'set_interface_vlan_layer3_ipv4_append_primary' => [
        'interface vlan:vlan',
        'ip address :ipv4_gateway/:ipv4_cidr_mask',
        'exit',
      ],
      'set_interface_vlan_layer3_ipv4_append_secondary' => [
        'ip route :ipv4_gateway/:ipv4_cidr_mask vlan :vlan',
      ],
      'set_interface_vlan_layer3_ipv6_append' => [
        'interface vlan:vlan',
        'ipv6 address :ipv6_gateway/:ipv6_cidr_mask',
        'exit',
      ],
      'set_interface_vlan_layer3_ipv6_append_secondary' => [
        'ipv6 route :ipv6_gateway/:ipv6_cidr_mask vlan :vlan',
      ],

      'interface_wipe_layer3_ipv4_append' => [
        'interface vlan:vlan',
        'no ip address :ipv4_gateway/:ipv4_cidr_mask',
        'exit',
      ],
      'interface_wipe_layer3_ipv4_append_secondary' => [
        'interface vlan:vlan',
        'no ip address :ipv4_gateway/:ipv4_cidr_mask secondary',
        'exit',
      ],
      'interface_wipe_layer3_ipv6_append' => [
        'interface vlan:vlan',
        'no ipv6 address :ipv6_gateway/:ipv6_cidr_mask',
        'exit',
      ],
      'interface_wipe_layer3_ipv6_append_secondary' => [
        'interface vlan:vlan',
        'no ipv6 address :ipv6_gateway/:ipv6_cidr_mask secondary',
        'exit',
      ],

      'interface_wipe' => ['interface :port', 'shutdown', 'exit'],

      'exit_config' => ['exit', 'copy running-config startup-config'],
      'exit_enable' => 'exit',
    ],
  ],
  'cisco' => [
    'ws' =>
      [
        'name' => 'Cisco WS',
      ] +
      $ciscoLayer3 +
      $ciscoDist +
      $ciscoDefault,
    'nexus' =>
      [
        'name' => 'Cisco Nexus',
        'set_interface_port_speed' => [
          'interface :port',
          'speed :speed',
          'exit',
        ],
      ] +
      $ciscoLayer3 +
      $ciscoDist +
      $ciscoDefault,
    'ios' =>
      [
        'name' => 'Cisco IOS',
        'set_interface_vlan' => [
          'interface :port',
          'switchport access vlan :vlan',
          'exit',

          'interface vlan :vlan',
          'ip address :ipv4_gateway :subnet_mask',
          'exit',
        ],
      ] +
      $ciscoLayer3 +
      $ciscoDist +
      $ciscoDefault,
  ],
  'mikrotik' => [
    'name' => 'MikroTik (limited support)',
    'enable' => '',
    'config' => '',
    'set_interface_vlan' => [
      '/interface bridge port add bridge=bridge1 interface=:port pvid=:vlan',
    ],
    'set_interface_vlan_tagged' => [
      '/interface bridge vlan add bridge=bridge1 tagged=:port vlan-ids=:vlan',
    ],
    'set_interface_port_on' => ['/interface Ethernet enable :port'],
    'set_interface_port_off' => ['/interface Ethernet disable :port'],
    'set_interface_port_speed' => [
      '/interface Ethernet set :port speed=:speed',
    ],
    'interface_wipe' => ['/interface Ethernet disable :port'],
    'exit_config' => '',
    'exit_enable' => '',
  ],
];
