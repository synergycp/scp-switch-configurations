<?php // Copyright 2014-present Zane Hooper. Do not use, modify, or distribute without written consent.

namespace App\Hub\Type;

use App\Support\ServiceProvider;

/**
 * Provide the Hub feature to the Application.
 */
class TypeServiceProvider extends ServiceProvider {
  /**
   * @var array
   */
  protected $providers = [TypeRoutesProvider::class];

  public function boot() {
    $type = $this->app->make(TypeService::class);

    $type->add(new DellNetworking\DellNetworkingType());
    $type->add(new DellPowerConnect6248\DellPowerConnect6248Type());
    $type->add(new Cisco\CiscoWSType());
    $type->add(new Cisco\CiscoIOSType());
    $type->add(new Cisco\CiscoNexusType());
    $type->add(new FS\FSType());
    $type->add(new Juniper\JuniperLegacyType());
    $type->add(new Juniper\JuniperELSType());
    $type->add(new Juniper\JuniperARPLockingType());
    $type->add(new Brocade\BrocadeType());
    $type->add(new Brocade\BrocadeFCXType());
    $type->add(new Arista\EOS\EOSType());
    $type->add(new Arista\EOS\EOSv4Type());
    $type->add(new Supermicro\SupermicroType());
    $type->add(new Supermicro\SMIS\SupermicroSMISType());
    $type->add(new Mikrotik\MikrotikType());

    $this->app->instance(TypeService::class, $type);
  }
}
