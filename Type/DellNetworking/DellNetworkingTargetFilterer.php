<?php // Copyright 2014-present Zane Hooper. Do not use, modify, or distribute without written consent.

namespace App\Hub\Type\DellNetworking;

use App\Bandwidth\Rtg\Target\TargetFilterer;

class DellNetworkingTargetFilterer extends TargetFilterer {
  /**
   * Prefixes to ignore when looking at interface names.
   *
   * @var array
   */
  protected $ignoredPrefixes = [
    'Link Aggregate', # Link Aggregate (Dell)
    'Vl', # Vlan (Dell)
    'CPU', # CPU (Dell)
  ];

  /*# List of "reserved" interfaces, i.e. those we don't care to monitor
    # This list is inclusive of only Cisco/Juniper
    private $reserved = array(
        "tap",  "pimd", "pime", "ipip",
        "lo0",  "gre",  "pd-",  "pe-",  "gr-", "ip-",
        "vt-",  "mt-",  "mtun", "Null", Loopback", "aal5",
        "-atm", "sc0",
    );*/
}
