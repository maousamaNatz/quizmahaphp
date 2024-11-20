<?php
use App\Helpers\AssetHelper;
?>

<div class="loading flex items-center justify-center fixed z-[9999] bg-white w-full h-full top-0 left-0">
    <div class="container flex items-center justify-center flex-col">
      <div class="relative flex justify-center items-center">
        <div class="absolute animate-spin rounded-full h-32 w-32 border-t-4 border-b-4 border-orange-500"></div>
        <img src="<?php echo AssetHelper::url('media/logos.png'); ?>" alt="logo" class="rounded-full h-20 w-20" id="loadingLogo">
      </div>
      <p id="loadingText" class="text-2xl font-bold" style="margin-top: 80px;">Loading...</p>
    </div>
</div>