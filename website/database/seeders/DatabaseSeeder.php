<?php

namespace Database\Seeders;

use App\Models\AppSetting;
use App\Models\AttackEvent;
use App\Models\MitigationRule;
use App\Models\TrafficSample;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $base = Carbon::create(2026, 6, 30, 14, 23, 45);

        User::updateOrCreate(
            ['email' => 'keanosy0319@gmail.com'],
            [
                'name' => 'Keano Nikko L. Sy',
                'password' => 'ddos_system2026',
            ],
        );

        foreach ([
            ['key' => 'router_health', 'value' => 'Healthy'],
            ['key' => 'router_host', 'value' => '172.16.0.1'],
            ['key' => 'router_utilization', 'value' => '92'],
            ['key' => 'cpu_utilization', 'value' => '64'],
            ['key' => 'memory_usage', 'value' => '51'],
            ['key' => 'system_uptime', 'value' => '99.98'],
        ] as $setting) {
            AppSetting::updateOrCreate(['key' => $setting['key']], ['value' => $setting['value']]);
        }

        $events = [
            ['attack_type' => 'UDP amplification', 'source_ip' => '10.23.1.18', 'target_ip' => '172.16.0.12', 'protocol' => 'UDP', 'severity' => 'critical', 'status' => 'open', 'packets' => 92410, 'anomaly_score' => 94, 'sla_minutes' => 5, 'occurred_at' => $base->copy()->subMinutes(2), 'description' => 'Large volume UDP amplification burst against the primary target.'],
            ['attack_type' => 'HTTP flood', 'source_ip' => '203.0.113.44', 'target_ip' => '172.16.0.12', 'protocol' => 'HTTP', 'severity' => 'high', 'status' => 'investigating', 'packets' => 78120, 'anomaly_score' => 88, 'sla_minutes' => 10, 'occurred_at' => $base->copy()->subMinutes(7), 'description' => 'Burst of repeated HTTP requests targeting the application layer.'],
            ['attack_type' => 'SYN scan', 'source_ip' => '198.51.100.77', 'target_ip' => '172.16.0.18', 'protocol' => 'TCP', 'severity' => 'medium', 'status' => 'acknowledged', 'packets' => 41980, 'anomaly_score' => 62, 'sla_minutes' => 30, 'occurred_at' => $base->copy()->subMinutes(16), 'description' => 'Repeated SYN attempts detected across several destinations.'],
            ['attack_type' => 'Low-rate probing', 'source_ip' => '192.0.2.88', 'target_ip' => '172.16.0.12', 'protocol' => 'ICMP', 'severity' => 'low', 'status' => 'resolved', 'packets' => 18400, 'anomaly_score' => 18, 'sla_minutes' => 30, 'occurred_at' => $base->copy()->subMinutes(31), 'description' => 'Slow probing pattern that was blocked and resolved.'],
        ];

        foreach ($events as $event) {
            AttackEvent::updateOrCreate(
                [
                    'source_ip' => $event['source_ip'],
                    'target_ip' => $event['target_ip'],
                    'occurred_at' => $event['occurred_at'],
                ],
                $event,
            );
        }

        $samples = [
            [0, 0],
            [24, 14],
            [32, 48],
            [46, 36],
            [42, 28],
            [58, 20],
            [64, 18],
            [70, 16],
            [83, 20],
            [92, 24],
            [104, 28],
            [118, 35],
        ];

        foreach ($samples as $index => [$normal, $suspicious]) {
            TrafficSample::updateOrCreate(
                ['sample_at' => Carbon::create(2026, 6, 30, 0, 0, 0)->addHours($index)],
                [
                    'normal_flows' => $normal,
                    'suspicious_flows' => $suspicious,
                    'threshold' => 70,
                ],
            );
        }

        foreach ([
            ['name' => 'Block UDP amplification source', 'target_ip' => '10.23.1.18', 'action' => 'deny', 'status' => 'active', 'country' => 'Russia', 'applied_at' => $base->copy()->subMinutes(2)],
            ['name' => 'HTTP flood throttling', 'target_ip' => '172.16.0.12', 'action' => 'rate_limit', 'status' => 'review', 'country' => 'China', 'applied_at' => $base->copy()->subMinutes(8)],
            ['name' => 'Temporary SYN ACL', 'target_ip' => '172.16.0.18', 'action' => 'acl_update', 'status' => 'queued', 'country' => 'Brazil', 'applied_at' => $base->copy()->subMinutes(14)],
            ['name' => 'DNS flood rate cap', 'target_ip' => '172.16.0.25', 'action' => 'rate_limit', 'status' => 'active', 'country' => 'Singapore', 'applied_at' => $base->copy()->subMinutes(35)],
        ] as $rule) {
            MitigationRule::updateOrCreate(['name' => $rule['name']], $rule);
        }
    }
}
