<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

class FetchStrideImages extends Command
{
    protected $signature = 'stride:fetch-images';
    protected $description = 'Download reference images and create local assets for Stride Footwear';

    public function handle(): int
    {
        $this->info('Starting Stride asset setup...');

        $publicStorage = storage_path('app/public');
        $dirs = [
            $publicStorage . '/products',
            $publicStorage . '/categories',
            $publicStorage . '/banners',
            public_path('images'),
        ];

        foreach ($dirs as $dir) {
            if (!File::exists($dir)) {
                File::makeDirectory($dir, 0755, true);
            }
        }

        // Create local SVG wordmark logo
        $logoSvg = <<<'SVG'
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 48" fill="none">
    <path d="M12 36C8 36 5 33 5 28C5 21 12 18 19 16C26 14 30 12 30 8C30 5 27 3 22 3C16 3 11 6 9 9L3 5C6 1 13 0 22 0C32 0 38 5 38 13C38 20 31 23 23 25C16 27 12 29 12 33C12 36 15 38 21 38C28 38 33 35 36 31L41 36C36 41 29 43 21 43C17 43 12 40 12 36Z" fill="#004725"/>
    <path d="M46 12H68V19H58V42H50V19H46V12Z" fill="#004725"/>
    <path d="M72 12H88C95 12 100 16 100 23C100 28 97 32 91 33L101 42H91L82 34H80V42H72V12ZM80 27H87C91 27 93 25 93 23C93 20 91 18 87 18H80V27Z" fill="#004725"/>
    <path d="M106 12H114V42H106V12Z" fill="#004725"/>
    <path d="M120 12H134C144 12 151 18 151 27C151 36 144 42 134 42H120V12ZM128 35H134C139 35 143 32 143 27C143 22 139 19 134 19H128V35Z" fill="#004725"/>
    <path d="M157 12H182V19H165V24H180V30H165V36H183V42H157V12Z" fill="#004725"/>
</svg>
SVG;
        File::put(public_path('images/stride-logo.svg'), $logoSvg);
        $this->info('Created local SVG logo at public/images/stride-logo.svg');

        // Reference images map
        $images = [
            // Hero
            'banners/hero-aeroglide.png' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuAmbHDIjGkgE9VqECmh-ya45OXMghaGdu3_QvvEcz39NHgY-qY7tSetQjjhsFkLN0LnDaNMtodxAFhdpBkam6GM9XROWhWOwe1epZHgpx8Cq8fWz1mDc2F69I6kDZe3d0agNO9dLOdu02x7fgNsrsaP3pCdnJV_KIfqOEJv9J6iKsw_yhZbboZ5a5GsXtJBoeP1i0raIo7d-DNIFEQ6GDXLISIkkJ2VqSGF5cRcENmeOBjuFmIoixNN',
            // Promos
            'banners/promo-runner.jpg' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuByMtzXgbywT0S3xe1G6meKPY1h4b06AoKSKHjaLm16mc37KDT8u56Pkg67l4gromobeklF-e9rdigX8VFc_fiSnl4a6cMqNqP7P2Q8FQlywooaXBvsQ6dySLTALJeUgRNc924ta5Ok0YjqvpOQ24OCqcsKECzvehGaJq0z23T0ETbTtYRs6G1XypEMGcK_2E13iCViuPZOPtmKwy6ZTorFVMDWihE7M09kX-PpdurbYlThZlBMl9dM',
            'banners/promo-plate.jpg' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuDLPd2dXmsQ4BkCdzyZiG8VKqlmipzmto1UHv0IagfgeDZ_qsSom9ZztH8GHFE--T_B8OyaljpPQv8kw8XKrYK0gP14viraKeKj8RLdZvLdOZpqw04GlhE2kzxsd6sN1U2Q4DPyUat5_1NUfTa7ngG9ECT-mmPGc-LyijOkBbO5iIljr4PBRipSLUN2mGvHTnDiQMLhPpD_lQ6Xu9STwWKxdNU4iVyHVLIQ17ihvmF4NcmVWzZEsJpg',
            // Categories
            'categories/running.jpg' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuAVZrgPhlwrTk4YGCOnEvxl2HzTp0hsshAIV8i3jM_mdp44MOtDIF3R7pvYxAuDhtS3EJC1ybD_jwWFjsrfy3CesFgKsmVuR45IGcQ2LlSWVvNy7d392jdV5tQaMebob3g2mff0SLYOlM0DAU_2jelWHshhCFkGrWkA-T4C9SczvPIu5MMLC-vtNZWS2GohGHz_6c54Q-pXD73HBR2Xftg1Tk7N5FB3KiiorKcGUsiukU0paV_47S4b',
            'categories/sneakers.jpg' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuBwsYGsZVN64OB_gbD03xm22DHNQmS9U9eFP9_6JpeWZnCPfTzXIe1v0ZxJ_Zs3_lVsFbK6bTR6rjX2Z-srRBkvRHbVroQqkVFI0CAWJ0vtYp4NKHuNY52gYgw_Sd4RjilAeDSISMut4YpmMUoLnbqwxPMBPEdsgw37S2rYR2VYRUrCV9ttKIJJFul25OrNcJd1XMKbL8UWE5Jx7yzq57UJxeyX_s0zp38WIU-MNPIQ6ihQ8pupqZDP',
            'categories/casual.jpg' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuB_Ou4uLCjKZJCv7vDNPJepzraaUtbgEz2Ds9Dm0hNQFTC-IUahOS7D0bcKBrM1kvYhJRKYSVeZfO0zaAy0LDh1eWBdm56ynw6lXCJ7AuPnOBGcWbpirXquEHNmnQWa8TodD-Oo-nMKEJK0tuaK8JXwXXGz-T0jnUFdKQf0p4GCOfA_NAAh8g-RJIfOpiVG3pQFz98hBldnODOrRHR7ToKbf9Lt5LuDzSFnQHgwD-LT6WVpokf0ORta',
            'categories/formal.jpg' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuCiSAo5ZN8m6RrPFzsvvyX7x_Doog4heX5bbM4SbZw2nbuiluwoMB38cUEDeKGIRQM4OnKpB8LsSQBzQhSHd-WOYzlpvZRQ4UcgBVD0-uGjhJHKZeFIDM7cYPzrQFr_qwu4YZ56Y2ne6UhgkRRNtwkrnS7RyAhYlbIeqMEzq2LK2mm3_46M1ZQkQ-81GjtEc_pztJBPnZ8NLK-q0MidxJInbOOX8HGyor7d5Qk_H23UCRG5dNL3by14',
            'categories/trail.jpg' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuA2uzsmLqUTLrFSV8_WNbXAXECMkkCOEOJl3tL9Y0h7XqDodq419gZI69wWo-lsNZCx8rSA8VqlYY2itutWxwzhg4rbjC6tJqi5lawq6w3YqMNjcvtGtYYEBlE3-nvUD63tgr-M7sfxAYHTerepkbdjXRze_89sulYu69_HnPgBrcstqegJJqXWN9ZonKsct0qOOBmQYpi2tIoPsGQP-k3CCSz6YzxOE2TZwjwFFTMeefGvopriLxw2',
            'categories/sandals.jpg' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuBisFa6rhZmwKoPIGIVBHxr5bo5wksBS8lsgridO5GHm-BlymNmRJ5T9mZlcS3uG23MLk081xlC2hf_Y9YiKt0kYECnKVat6hlXEj5Sa8Hat0ONFhuvlGvZ6SM7CAUXjodFbQpmS4hzCwd5t20xvDrmIGH6TLKtKB-PRcZ26ETxEP0533cHRoL7et1wJPpeWxTr9EF3PDTB_hnOxPSO2draE6O9ySUqDym_qleAEZ8A2Xot-eeyDsv9',
            'categories/kids.jpg' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuBPy4MMvcoKywBLtcjvB9vxRzwg_CXUO7qCygQP5w6BSXiIGHhskwpxlM8byMxSnvTMgqaAfxzt10c98GffNt2_tqFbvSZrzMC39QKw0Lb-GoOzp8Jaipl9UWgzV1UAKwWzC0TgHaX084uPPH81wgVvdBrE3_IEdpjWd38dX4_ITQAxADgFUHGBWPvMvmkMgFDE6NTYuhDAzfKfRrmAoR7Mu3Hg7gU84-fVg4Lfrb5yaDSGKVT0PL0h',
            'categories/accessories.jpg' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuBC3s6JHTIuFpT5XYPkOsLfyiC6njetWJ3N8VPPN-hAZCiRHWlx027yeI9tERQagJQEMgV11_gRgrkRmMn0fAskMX9PIy11C3OTNpNTehoE0EMx5PpyH8g7_7ogSJR2UZPUBQixBaUTlZy8spErKP23zLbvEv2FmbksxpplfbudIfqu9gOZZm4H_l4p-GnORIupVVQYam0Mwnkv3wE-0mdJeN4ErjMeoVTNFHW1EFlfkbHXbrCpS_tJ',
            // Trending Products
            'products/aeroglide-carbon.png' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuCDB_yvThLRqssvEtUkYcGYVFqvf9kAoH3TdWgfAQAd9o_trwbqDrSn9GwFP824IN1OD2wHH-qcS4tgW8BzASafWxh3k7hMcGIQFTZpJp25-UCQBTqEVQaRgzyK75PMkhq22ilfeuTRAyin7jDgEQsnmZVudPcLKpPpPXp4hrjhsRID3nUFDVkws_pnRGA3JKlz8-U3E-HbzCRIQZrERp6_dyEwknv5iRqQ0s-DKPS6V-qeD-34QVBz',
            'products/pacemaker-3.png' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuC96bbrL9r7W8mxAogCHb_g2iPmMzf9lK8w7SeNHK136Xzzvu3w2eVaeRKxqIYOazrvEwtC_CXQARLqf7Obu7NqXVyKW4SdtokYIZuSx9HJurMkRv2zIf1_jf7OkPU-5uiczmxn_wR7A7-zuw2i-0DdO3Zve__WlSeILlq0JEginyxrojscDCTHuyf55cL8FdkhZ-wxd3Lqgfr379_gf46ED2_sKeBtJw9ZtUEhyQAU0mRqBRlAcPCT',
            'products/trailventure-gtx.png' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuDfsifbzVa3mCBn8HTLgrDnTURUpYfBwVCMw_t-R_d_jsWLN1OZsRkNxxTvlAu1PuCtabPZdFPARVpKCrFGu7JLWxOs1QpWhAj70sIjdd64itDSDAgfgm5y_r-MIoUG9vIasdC0FKNjLSFgn_ETRTaDKFlv0Wu8ZYCBWJ9I0DDjin-s9_higORhe-Cl5SdadBDJKOfWWa7lghA4eL9JPeqA9DqK0rtw7t9VGm8BvNsntn21-hAtWNHV',
            'products/cloudshift-slip.png' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuBDELuOaFFsaYDGn0rXazJWIu0B9ai2fjSH5fGEwtgldaIjlYySz0w65GP4AOJA_O4q79ZECqf0ahA735isXLe8t3ZX9A8hMLfzIKuHwV3jPomYT5ltfXMkAtTeV0SmN5JnMfdtLge8fdbK4QdRw7WGZ578tAGgi2dw3iUvQlRR1kAQPZ7dH7Osi6Tdqs6uHW7PV_BRgCbR8V8ROwW-sYGoDEUldu2D3lefnrVIqwZnAWb92Y83klp8',
            'products/urbanstance-low.png' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuANR2m2MmPwjLzl787V_hDjZtedXDVnYpflGyH5LkODAQsV9bIFdeZP8N6BbQb94neHgYuu5neZ1KdDOaP68OX1wWoAjGHEHypVQWncSIm4qChJ7OfgPbwrB-nPnA__7OvUDjTk1r-GJTFmdjPWxXaHtjzKFXxAS0hzfvUo6XItknLNRBHnixYUgDNSNmB2OvMMmvf-oKoP_OqF6oj8kgeNzatv0BoLedBNmMrI50jOv49AQ5ljjNiv',
            // User avatar
            'user-avatar.jpg' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuC69xUfE36DBte1fDtiZwC25Oxf8vH_SFfDJhNMj0A3QRkXvniCqcr08MrQWbMPEBq4Rhc0mrPTEMmudiSu2FCuJHrd9jnfiBgYLubMejIzjMBodMGg3SFI28NxlkhHSMEx4eQfWnI8Qp0Okzfae0m_HoKYFLY-lJ1sLroCTJDA5ajJcF1k9BXIFrnzci8-XOpXwFtvWCOlEWCt7LkYQIys5C4CBnYaxXujUpBhoawWuaIvfjc_EDkd',
        ];

        foreach ($images as $relativePath => $url) {
            $destPath = $publicStorage . '/' . $relativePath;
            if (File::exists($destPath) && File::size($destPath) > 500) {
                $this->line("Already exists: {$relativePath}");
                continue;
            }

            $this->info("Fetching: {$relativePath}...");
            try {
                $response = Http::timeout(10)->withoutVerifying()->get($url);
                if ($response->successful()) {
                    File::put($destPath, $response->body());
                    $this->info("Saved: {$relativePath}");
                    continue;
                }
            } catch (\Exception $e) {
                $this->warn("Failed downloading {$url}: " . $e->getMessage());
            }

            // Generate clean SVG placeholder if download fails
            $this->generatePlaceholder($destPath, basename($relativePath));
            $this->info("Generated fallback placeholder for {$relativePath}");
        }

        // Link storage
        $this->call('storage:link');

        $this->info('Stride image fetching & setup completed!');
        return Command::SUCCESS;
    }

    private function generatePlaceholder(string $path, string $label): void
    {
        $label = htmlspecialchars(str_replace(['-', '_', '.png', '.jpg'], [' ', ' ', '', ''], $label));
        $svg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 600 600" width="100%" height="100%">
    <rect width="600" height="600" fill="#e9edff"/>
    <circle cx="300" cy="300" r="180" fill="#ffffff" opacity="0.6"/>
    <path d="M180 340C200 280 280 250 360 270C400 280 440 310 450 330C450 350 420 370 360 370C280 370 200 370 180 340Z" fill="#1f5f3a" opacity="0.85"/>
    <path d="M160 350C220 350 420 350 460 350C470 350 470 370 450 370C410 370 200 370 150 370C140 370 140 350 160 350Z" fill="#004725"/>
    <text x="300" y="440" font-family="'Plus Jakarta Sans', sans-serif" font-size="20" font-weight="600" fill="#404941" text-anchor="middle">STRIDE FOOTWEAR</text>
    <text x="300" y="470" font-family="'Inter', sans-serif" font-size="14" fill="#707971" text-anchor="middle">{$label}</text>
</svg>
SVG;
        File::put($path, $svg);
    }
}
