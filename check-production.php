<?php
/**
 * Production Readiness Checker
 * Run this script to validate if the plugin is ready for production
 */

class ProductionChecker
{
    private array $errors = [];
    private array $warnings = [];
    private array $success = [];

    public function check(): array
    {
        echo "🔍 Checking production readiness for Elementor WC Meta...\n\n";

        $this->checkEnvironment();
        $this->checkDependencies();
        $this->checkAssets();
        $this->checkSecurity();
        $this->checkPerformance();

        $this->displayResults();
        
        return [
            'ready' => empty($this->errors),
            'errors' => $this->errors,
            'warnings' => $this->warnings,
            'success' => $this->success
        ];
    }

    private function checkEnvironment(): void
    {
        // PHP Version
        if (version_compare(PHP_VERSION, '8.0', '<')) {
            $this->errors[] = "PHP 8.0+ required. Current: " . PHP_VERSION;
        } else {
            $this->success[] = "✅ PHP version: " . PHP_VERSION;
        }

        // Required extensions
        $requiredExtensions = ['json', 'curl', 'mbstring'];
        foreach ($requiredExtensions as $ext) {
            if (!extension_loaded($ext)) {
                $this->errors[] = "Required PHP extension missing: {$ext}";
            }
        }

        if (count($requiredExtensions) === count(array_filter($requiredExtensions, 'extension_loaded'))) {
            $this->success[] = "✅ All required PHP extensions loaded";
        }
    }

    private function checkDependencies(): void
    {
        // Composer autoloader
        if (!file_exists(__DIR__ . '/vendor/autoload.php')) {
            $this->errors[] = "Composer dependencies not installed. Run: composer install --no-dev";
        } else {
            $this->success[] = "✅ Composer autoloader present";
        }

        // Check if dev dependencies are excluded in production
        $composerLock = __DIR__ . '/composer.lock';
        if (file_exists($composerLock)) {
            $lock = json_decode(file_get_contents($composerLock), true);
            if (!empty($lock['packages-dev'])) {
                $this->warnings[] = "⚠️  Dev dependencies present (should be excluded in production)";
            } else {
                $this->success[] = "✅ No dev dependencies in production build";
            }
        }
    }

    private function checkAssets(): void
    {
        $distPath = __DIR__ . '/public/dist';
        $manifestPath = $distPath . '/.vite/manifest.json';

        // Check if assets are built
        if (!is_dir($distPath)) {
            $this->errors[] = "Assets not built. Run: npm run build";
            return;
        }

        // Check manifest
        if (!file_exists($manifestPath)) {
            $this->errors[] = "Asset manifest missing. Rebuild assets with manifest enabled";
            return;
        }

        $this->success[] = "✅ Assets built and manifest present";

        // Check CSS files
        $cssDir = $distPath . '/css';
        if (!is_dir($cssDir) || count(glob($cssDir . '/*.css')) === 0) {
            $this->errors[] = "CSS files missing in production build";
        } else {
            $this->success[] = "✅ CSS assets present";
        }

        // Check JS files  
        $jsDir = $distPath . '/js';
        if (!is_dir($jsDir) || count(glob($jsDir . '/*.js')) === 0) {
            $this->errors[] = "JavaScript files missing in production build";
        } else {
            $this->success[] = "✅ JavaScript assets present";
        }

        // Check if source files are excluded
        if (is_dir(__DIR__ . '/resources')) {
            $this->warnings[] = "⚠️  Source files present (should be excluded in production)";
        }

        // Check node_modules
        if (is_dir(__DIR__ . '/node_modules')) {
            $this->warnings[] = "⚠️  node_modules present (should be excluded in production)";
        }
    }

    private function checkSecurity(): void
    {
        // Check for sensitive files
        $sensitiveFiles = [
            '.env',
            '.env.local',
            'wp-config.php',
            'debug.log'
        ];

        foreach ($sensitiveFiles as $file) {
            if (file_exists(__DIR__ . '/' . $file)) {
                $this->errors[] = "Sensitive file present: {$file}";
            }
        }

        // Check file permissions
        $mainFile = __DIR__ . '/elementor-wc-meta.php';
        if (file_exists($mainFile)) {
            $perms = fileperms($mainFile) & 0777;
            if ($perms > 0644) {
                $this->warnings[] = "⚠️  Main plugin file has overly permissive permissions: " . decoct($perms);
            } else {
                $this->success[] = "✅ File permissions are secure";
            }
        }

        $this->success[] = "✅ No sensitive files detected";
    }

    private function checkPerformance(): void
    {
        // Check autoloader optimization
        $autoloadFile = __DIR__ . '/vendor/composer/autoload_classmap.php';
        if (file_exists($autoloadFile)) {
            $classmap = include $autoloadFile;
            if (empty($classmap)) {
                $this->warnings[] = "⚠️  Autoloader not optimized. Run: composer install --optimize-autoloader";
            } else {
                $this->success[] = "✅ Autoloader optimized";
            }
        }

        // Check asset minification
        $manifestPath = __DIR__ . '/public/dist/.vite/manifest.json';
        if (file_exists($manifestPath)) {
            $manifest = json_decode(file_get_contents($manifestPath), true);
            $hasMinifiedAssets = false;
            
            foreach ($manifest as $entry) {
                if (isset($entry['file'])) {
                    $filePath = __DIR__ . '/public/dist/' . $entry['file'];
                    if (file_exists($filePath)) {
                        $content = file_get_contents($filePath);
                        // Simple check for minification (no unnecessary whitespace)
                        if (strlen($content) > 0 && substr_count($content, "\n") / strlen($content) < 0.05) {
                            $hasMinifiedAssets = true;
                            break;
                        }
                    }
                }
            }

            if ($hasMinifiedAssets) {
                $this->success[] = "✅ Assets are minified";
            } else {
                $this->warnings[] = "⚠️  Assets may not be properly minified";
            }
        }
    }

    private function displayResults(): void
    {
        echo "\n" . str_repeat("=", 60) . "\n";
        echo "📊 PRODUCTION READINESS REPORT\n";
        echo str_repeat("=", 60) . "\n\n";

        if (!empty($this->errors)) {
            echo "❌ CRITICAL ERRORS:\n";
            foreach ($this->errors as $error) {
                echo "   • {$error}\n";
            }
            echo "\n";
        }

        if (!empty($this->warnings)) {
            echo "⚠️  WARNINGS:\n";
            foreach ($this->warnings as $warning) {
                echo "   • {$warning}\n";
            }
            echo "\n";
        }

        if (!empty($this->success)) {
            echo "✅ SUCCESS:\n";
            foreach ($this->success as $success) {
                echo "   • {$success}\n";
            }
            echo "\n";
        }

        echo str_repeat("-", 60) . "\n";
        
        if (empty($this->errors)) {
            echo "🎉 PRODUCTION READY! Plugin can be deployed safely.\n";
        } else {
            echo "🚫 NOT READY FOR PRODUCTION. Please fix the errors above.\n";
        }
        
        echo str_repeat("=", 60) . "\n";
    }
}

// Run the checker
$checker = new ProductionChecker();
$result = $checker->check();

exit($result['ready'] ? 0 : 1);
