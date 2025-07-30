<?php

namespace Sfx\UnifiPortal;

if (!defined('UNI_PLUGIN_PATH')) {
    exit;
}

return [
    [
        "label" => "List Clients",
        "value" => Utils::xorEncrypt("list_clients"),
    ],
    [
        "label" => "List Devices",
        "value" => Utils::xorEncrypt("list_devices"),
    ],
    [
        "label" => "List Self",
        "value" => Utils::xorEncrypt("list_self"),
    ],
    [
        "label" => "List Sites",
        "value" => Utils::xorEncrypt("list_sites"),
    ],
    [
        "label" => "List Settings",
        "value" => Utils::xorEncrypt("list_settings"),
    ],
    [
        "label" => "List Users",
        "value" => Utils::xorEncrypt("list_users"),
    ],
    // [
    //     "label" => "Adopt Device",
    //     "value" => Utils::xorEncrypt("adopt_device"),
    // ],
    // [
    //     "label" => "Advanced Adopt Device",
    //     "value" => Utils::xorEncrypt("advanced_adopt_device"),
    // ],
    // [
    //     "label" => "Archive Alarm",
    //     "value" => Utils::xorEncrypt("archive_alarm"),
    // ],
    // [
    //     "label" => "Assign Existing Admin",
    //     "value" => Utils::xorEncrypt("assign_existing_admin"),
    // ],
    // [
    //     "label" => "Authorize Guest",
    //     "value" => Utils::xorEncrypt("authorize_guest"),
    // ],
    // [
    //     "label" => "Block STA",
    //     "value" => Utils::xorEncrypt("block_sta"),
    // ],
    // [
    //     "label" => "Cancel Rolling Upgrade",
    //     "value" => Utils::xorEncrypt("cancel_rolling_upgrade"),
    // ],
    [
        "label" => "Check Controller Update",
        "value" => Utils::xorEncrypt("check_controller_update"),
    ],
    [
        "label" => "Check Firmware Update",
        "value" => Utils::xorEncrypt("check_firmware_update"),
    ],
    [
        "label" => "Cmd Stat",
        "value" => Utils::xorEncrypt("cmd_stat"),
    ],
    [
        "label" => "Count Alarms",
        "value" => Utils::xorEncrypt("count_alarms"),
    ],
    // [
    //     "label" => "Create AP Group",
    //     "value" => Utils::xorEncrypt("create_apgroup"),
    // ],
    // [
    //     "label" => "Create DynamicDNS",
    //     "value" => Utils::xorEncrypt("create_dynamicdns"),
    // ],
    // [
    //     "label" => "Create Firewall Group",
    //     "value" => Utils::xorEncrypt("create_firewallgroup"),
    // ],
    // [
    //     "label" => "Create Hotspot OP",
    //     "value" => Utils::xorEncrypt("create_hotspotop"),
    // ],
    // [
    //     "label" => "Create Network",
    //     "value" => Utils::xorEncrypt("create_network"),
    // ],
    // [
    //     "label" => "Create Radius Account",
    //     "value" => Utils::xorEncrypt("create_radius_account"),
    // ],
    // [
    //     "label" => "Create User",
    //     "value" => Utils::xorEncrypt("create_user"),
    // ],
    // [
    //     "label" => "Create User Group",
    //     "value" => Utils::xorEncrypt("create_usergroup"),
    // ],
    // [
    //     "label" => "Create Voucher",
    //     "value" => Utils::xorEncrypt("create_voucher"),
    // ],
    // [
    //     "label" => "Create WLAN",
    //     "value" => Utils::xorEncrypt("create_wlan"),
    // ],
    [
        "label" => "Custom API Request",
        "value" => Utils::xorEncrypt("custom_api_request"),
    ],
    // [
    //     "label" => "Delete AP Group",
    //     "value" => Utils::xorEncrypt("delete_apgroup"),
    // ],
    // [
    //     "label" => "Delete Device",
    //     "value" => Utils::xorEncrypt("delete_device"),
    // ],

    // [
    //     "label" => "Delete Firewall Group",
    //     "value" => Utils::xorEncrypt("delete_firewallgroup"),
    // ],
    // [
    //     "label" => "Delete Network",
    //     "value" => Utils::xorEncrypt("delete_network"),
    // ],
    // [
    //     "label" => "Delete Radius Account",
    //     "value" => Utils::xorEncrypt("delete_radius_account"),
    // ],
    // [
    //     "label" => "Delete Site",
    //     "value" => Utils::xorEncrypt("delete_site"),
    // ],
    // [
    //     "label" => "Delete User Group",
    //     "value" => Utils::xorEncrypt("delete_usergroup"),
    // ],
    // [
    //     "label" => "Delete WLAN",
    //     "value" => Utils::xorEncrypt("delete_wlan"),
    // ],
    // [
    //     "label" => "Disable AP",
    //     "value" => Utils::xorEncrypt("disable_ap"),
    // ],
    // [
    //     "label" => "Disable WLAN",
    //     "value" => Utils::xorEncrypt("disable_wlan"),
    // ],
    // [
    //     "label" => "Edit AP Group",
    //     "value" => Utils::xorEncrypt("edit_apgroup"),
    // ],
    // [
    //     "label" => "Edit Client Fixed IP",
    //     "value" => Utils::xorEncrypt("edit_client_fixedip"),
    // ],
    // [
    //     "label" => "Edit Client Name",
    //     "value" => Utils::xorEncrypt("edit_client_name"),
    // ],
    // [
    //     "label" => "Edit Firewall Group",
    //     "value" => Utils::xorEncrypt("edit_firewallgroup"),
    // ],
    // [
    //     "label" => "Edit User Group",
    //     "value" => Utils::xorEncrypt("edit_usergroup"),
    // ],
    // [
    //     "label" => "Extend Guest Validity",
    //     "value" => Utils::xorEncrypt("extend_guest_validity"),
    // ],
    // [
    //     "label" => "Forget STA",
    //     "value" => Utils::xorEncrypt("forget_sta"),
    // ],
    // [
    //     "label" => "Generate Backup",
    //     "value" => Utils::xorEncrypt("generate_backup"),
    // ],
    // [
    //     "label" => "Generate Backup Site",
    //     "value" => Utils::xorEncrypt("generate_backup_site"),
    // ],
    [
        "label" => "Get Class Version",
        "value" => Utils::xorEncrypt("get_class_version"),
    ],
    [
        "label" => "Get Cookie",
        "value" => Utils::xorEncrypt("get_cookie"),
    ],
    [
        "label" => "Get Cookies",
        "value" => Utils::xorEncrypt("get_cookies"),
    ],
    [
        "label" => "Get Curl Connection Timeout",
        "value" => Utils::xorEncrypt("get_curl_connection_timeout"),
    ],
    [
        "label" => "Get Curl HTTP Version",
        "value" => Utils::xorEncrypt("get_curl_http_version"),
    ],
    [
        "label" => "Get Curl Method",
        "value" => Utils::xorEncrypt("get_curl_method"),
    ],
    [
        "label" => "Get Curl Request Timeout",
        "value" => Utils::xorEncrypt("get_curl_request_timeout"),
    ],
    [
        "label" => "Get Curl SSL Verify Host",
        "value" => Utils::xorEncrypt("get_curl_ssl_verify_host"),
    ],
    [
        "label" => "Get Curl SSL Verify Peer",
        "value" => Utils::xorEncrypt("get_curl_ssl_verify_peer"),
    ],
    [
        "label" => "Get Debug",
        "value" => Utils::xorEncrypt("get_debug"),
    ],
    [
        "label" => "Get Is Unifi OS",
        "value" => Utils::xorEncrypt("get_is_unifi_os"),
    ],
    [
        "label" => "Get Last Error Message",
        "value" => Utils::xorEncrypt("get_last_error_message"),
    ],
    [
        "label" => "Get Last Results Raw",
        "value" => Utils::xorEncrypt("get_last_results_raw"),
    ],
    [
        "label" => "Get Site",
        "value" => Utils::xorEncrypt("get_site"),
    ],
    [
        "label" => "Invite Admin",
        "value" => Utils::xorEncrypt("invite_admin"),
    ],
    [
        "label" => "LED Override",
        "value" => Utils::xorEncrypt("led_override"),
    ],
    [
        "label" => "List Admins",
        "value" => Utils::xorEncrypt("list_admins"),
    ],
    [
        "label" => "List All Admins",
        "value" => Utils::xorEncrypt("list_all_admins"),
    ],
    [
        "label" => "List Alarms",
        "value" => Utils::xorEncrypt("list_alarms"),
    ],
    [
        "label" => "List APs",
        "value" => Utils::xorEncrypt("list_aps"),
    ],
    [
        "label" => "List Backups",
        "value" => Utils::xorEncrypt("list_backups"),
    ],
    [
        "label" => "List Country Codes",
        "value" => Utils::xorEncrypt("list_country_codes"),
    ],
    [
        "label" => "List Current Channels",
        "value" => Utils::xorEncrypt("list_current_channels"),
    ],
    [
        "label" => "List Dashboard",
        "value" => Utils::xorEncrypt("list_dashboard"),
    ],
    [
        "label" => "List Device Name Mappings",
        "value" => Utils::xorEncrypt("list_device_name_mappings"),
    ],
    [
        "label" => "List Device States",
        "value" => Utils::xorEncrypt("list_device_states"),
    ],
    [
        "label" => "List Devices Basic",
        "value" => Utils::xorEncrypt("list_devices_basic"),
    ],
    [
        "label" => "List DynamicDNS",
        "value" => Utils::xorEncrypt("list_dynamicdns"),
    ],
    [
        "label" => "List Events",
        "value" => Utils::xorEncrypt("list_events"),
    ],
    [
        "label" => "List Extension",
        "value" => Utils::xorEncrypt("list_extension"),
    ],
    [
        "label" => "List Firewall Groups",
        "value" => Utils::xorEncrypt("list_firewallgroups"),
    ],
    [
        "label" => "List Firmware",
        "value" => Utils::xorEncrypt("list_firmware"),
    ],
    [
        "label" => "List Guests",
        "value" => Utils::xorEncrypt("list_guests"),
    ],
    [
        "label" => "List Health",
        "value" => Utils::xorEncrypt("list_health"),
    ],
    [
        "label" => "List Hotspot OP",
        "value" => Utils::xorEncrypt("list_hotspotop"),
    ],
    [
        "label" => "List Known Rogue APs",
        "value" => Utils::xorEncrypt("list_known_rogueaps"),
    ],
    [
        "label" => "List Network Conf",
        "value" => Utils::xorEncrypt("list_networkconf"),
    ],
    [
        "label" => "List Port Conf",
        "value" => Utils::xorEncrypt("list_portconf"),
    ],
    [
        "label" => "List Portforward Stats",
        "value" => Utils::xorEncrypt("list_portforward_stats"),
    ],
    [
        "label" => "List Portforwarding",
        "value" => Utils::xorEncrypt("list_portforwarding"),
    ],
    [
        "label" => "List Radius Accounts",
        "value" => Utils::xorEncrypt("list_radius_accounts"),
    ],
    [
        "label" => "List Radius Profiles",
        "value" => Utils::xorEncrypt("list_radius_profiles"),
    ],
    [
        "label" => "List Tags",
        "value" => Utils::xorEncrypt("list_tags"),
    ],
    [
        "label" => "List WLAN Groups",
        "value" => Utils::xorEncrypt("list_wlan_groups"),
    ],
    [
        "label" => "List WLAN Conf",
        "value" => Utils::xorEncrypt("list_wlanconf"),
    ],
    [
        "label" => "Locate AP",
        "value" => Utils::xorEncrypt("locate_ap"),
    ],
    // [
    //     "label" => "Login",
    //     "value" => Utils::xorEncrypt("login"),
    // ],
    // [
    //     "label" => "Logout",
    //     "value" => Utils::xorEncrypt("logout"),
    // ],
    // [
    //     "label" => "Move Device",
    //     "value" => Utils::xorEncrypt("move_device"),
    // ],
    // [
    //     "label" => "Power Cycle Switch Port",
    //     "value" => Utils::xorEncrypt("power_cycle_switch_port"),
    // ],
    // [
    //     "label" => "Reboot Cloudkey",
    //     "value" => Utils::xorEncrypt("reboot_cloudkey"),
    // ],
    // [
    //     "label" => "Rename AP",
    //     "value" => Utils::xorEncrypt("rename_ap"),
    // ],
    // [
    //     "label" => "Revoke Admin",
    //     "value" => Utils::xorEncrypt("revoke_admin"),
    // ],
    // [
    //     "label" => "Revoke Voucher",
    //     "value" => Utils::xorEncrypt("revoke_voucher"),
    // ],
    // [
    //     "label" => "Set AP Radio Settings",
    //     "value" => Utils::xorEncrypt("set_ap_radiosettings"),
    // ],
    // [
    //     "label" => "Set AP WLAN Group",
    //     "value" => Utils::xorEncrypt("set_ap_wlangroup"),
    // ],
    // [
    //     "label" => "Set Connection Timeout",
    //     "value" => Utils::xorEncrypt("set_connection_timeout"),
    // ],
    // [
    //     "label" => "Set Cookies",
    //     "value" => Utils::xorEncrypt("set_cookies"),
    // ],
    // [
    //     "label" => "Set Curl HTTP Version",
    //     "value" => Utils::xorEncrypt("set_curl_http_version"),
    // ],
    // [
    //     "label" => "Set Curl Request Timeout",
    //     "value" => Utils::xorEncrypt("set_curl_request_timeout"),
    // ],
    // [
    //     "label" => "Set Curl SSL Verify Host",
    //     "value" => Utils::xorEncrypt("set_curl_ssl_verify_host"),
    // ],
    [
        "label" => "Site LEDs",
        "value" => Utils::xorEncrypt("site_leds"),
    ],
    [
        "label" => "Spectrum Scan",
        "value" => Utils::xorEncrypt("spectrum_scan"),
    ],
    [
        "label" => "Spectrum Scan State",
        "value" => Utils::xorEncrypt("spectrum_scan_state"),
    ],
    [
        "label" => "Start Rolling Upgrade",
        "value" => Utils::xorEncrypt("start_rolling_upgrade"),
    ],
    [
        "label" => "Stat 5 Minutes APs",
        "value" => Utils::xorEncrypt("stat_5minutes_aps"),
    ],
    [
        "label" => "Stat 5 Minutes Gateway",
        "value" => Utils::xorEncrypt("stat_5minutes_gateway"),
    ],
    [
        "label" => "Stat 5 Minutes Site",
        "value" => Utils::xorEncrypt("stat_5minutes_site"),
    ],
    [
        "label" => "Stat 5 Minutes User",
        "value" => Utils::xorEncrypt("stat_5minutes_user"),
    ],
    [
        "label" => "Stat All Users",
        "value" => Utils::xorEncrypt("stat_allusers"),
    ],
    [
        "label" => "Stat Auths",
        "value" => Utils::xorEncrypt("stat_auths"),
    ],
    [
        "label" => "Stat Client",
        "value" => Utils::xorEncrypt("stat_client"),
    ],
    [
        "label" => "Stat Daily APs",
        "value" => Utils::xorEncrypt("stat_daily_aps"),
    ],
    [
        "label" => "Stat Daily Gateway",
        "value" => Utils::xorEncrypt("stat_daily_gateway"),
    ],
    [
        "label" => "Stat Daily Site",
        "value" => Utils::xorEncrypt("stat_daily_site"),
    ],
    [
        "label" => "Stat Daily User",
        "value" => Utils::xorEncrypt("stat_daily_user"),
    ],
    [
        "label" => "Stat Full Status",
        "value" => Utils::xorEncrypt("stat_full_status"),
    ],
    [
        "label" => "Stat Hourly APs",
        "value" => Utils::xorEncrypt("stat_hourly_aps"),
    ],
    [
        "label" => "Stat Hourly Gateway",
        "value" => Utils::xorEncrypt("stat_hourly_gateway"),
    ],
    [
        "label" => "Stat Hourly Site",
        "value" => Utils::xorEncrypt("stat_hourly_site"),
    ],
    [
        "label" => "Stat Hourly User",
        "value" => Utils::xorEncrypt("stat_hourly_user"),
    ],
    [
        "label" => "Stat IPS Events",
        "value" => Utils::xorEncrypt("stat_ips_events"),
    ],
    [
        "label" => "Stat Monthly APs",
        "value" => Utils::xorEncrypt("stat_monthly_aps"),
    ],
    [
        "label" => "Stat Monthly Gateway",
        "value" => Utils::xorEncrypt("stat_monthly_gateway"),
    ],
    [
        "label" => "Stat Monthly Site",
        "value" => Utils::xorEncrypt("stat_monthly_site"),
    ],
    [
        "label" => "Stat Monthly User",
        "value" => Utils::xorEncrypt("stat_monthly_user"),
    ],
    [
        "label" => "Stat Payment",
        "value" => Utils::xorEncrypt("stat_payment"),
    ],
    [
        "label" => "Stat Sessions",
        "value" => Utils::xorEncrypt("stat_sessions"),
    ],
    [
        "label" => "Stat Sites",
        "value" => Utils::xorEncrypt("stat_sites"),
    ],
    [
        "label" => "Stat Speedtest Results",
        "value" => Utils::xorEncrypt("stat_speedtest_results"),
    ],
    [
        "label" => "Stat STA Sessions Latest",
        "value" => Utils::xorEncrypt("stat_sta_sessions_latest"),
    ],
    [
        "label" => "Stat Status",
        "value" => Utils::xorEncrypt("stat_status"),
    ],
    [
        "label" => "Stat Sysinfo",
        "value" => Utils::xorEncrypt("stat_sysinfo"),
    ],
    [
        "label" => "Stat Voucher",
        "value" => Utils::xorEncrypt("stat_voucher"),
    ],
];
