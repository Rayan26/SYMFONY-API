<?php

// This file has been auto-generated by the Symfony Dependency Injection Component for internal use.

if (\class_exists(\ContainerWxiyMBx\App_KernelTestDebugContainer::class, false)) {
    // no-op
} elseif (!include __DIR__.'/ContainerWxiyMBx/App_KernelTestDebugContainer.php') {
    touch(__DIR__.'/ContainerWxiyMBx.legacy');

    return;
}

if (!\class_exists(App_KernelTestDebugContainer::class, false)) {
    \class_alias(\ContainerWxiyMBx\App_KernelTestDebugContainer::class, App_KernelTestDebugContainer::class, false);
}

return new \ContainerWxiyMBx\App_KernelTestDebugContainer([
    'container.build_hash' => 'WxiyMBx',
    'container.build_id' => 'cd8947e5',
    'container.build_time' => 1624486638,
], __DIR__.\DIRECTORY_SEPARATOR.'ContainerWxiyMBx');