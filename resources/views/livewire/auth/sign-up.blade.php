<div>
    <div class="block sm:hidden mb-8">
        <button wire:click="navigateToSignIn" type="button"
            class="rounded-full bg-[#9e9d9d] p-2"><x-eva-arrow-ios-back-outline class="size-6" /></button>
    </div>
    <div class="flex flex-col gap-2">
        <p class="text-2xl text-black">Sign up</p>
        <p class="text-lg text-black">Sign up to get access the features of “WeatherPREDIK”</p>
    </div>
    <div class="flex flex-col gap-10 mt-10">
        <div class="flex flex-col gap-2">
            <p class="text-lg">Email</p>
            <div class="space-y-3">
                <input wire:model.live.debounce.200ms="email" type="text"
                    class="py-2.5 sm:py-3 px-4 block w-full border border-black rounded-lg
                 sm:text-sm focus:border-blue-500  disabled:pointer-events-none
                  bg-white  text-[#000000CC] placeholder-[#000000B3]
                   focus:ring-neutral-600"
                    placeholder="Enter your email">
            </div>
            @error('email')
                <div class="flex flex-row gap-2">
                    <x-feathericon-info class="size-6 text-red-500"/>
                    <span class=" text-red-500">{{ $message }}</span>
                </div>
            @enderror
        </div>

        <div class="flex flex-col gap-2">
            <p class="text-lg">Password</p>
            <div class="relative">
                <input wire:model.live.debounce.200ms="password" id="hs-toggle-password" type="password"
                    class="py-2.5 sm:py-3 px-4 block w-full border border-black rounded-lg
                 sm:text-sm focus:border-blue-500 disabled:pointer-events-none
                  bg-white  text-[#000000CC] placeholder-[#000000B3]
                   focus:ring-neutral-600"
                    placeholder="Enter password">
                <button type="button" data-hs-toggle-password='{
        "target": "#hs-toggle-password"
      }'
                    class="absolute inset-y-0 end-0 flex items-center z-20 px-3 cursor-pointer text-gray-400 rounded-e-md focus:outline-hidden focus:text-black dark:text-neutral-600">
                    <svg class="shrink-0 size-3.5" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path class="hs-password-active:hidden" d="M9.88 9.88a3 3 0 1 0 4.24 4.24"></path>
                        <path class="hs-password-active:hidden"
                            d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"></path>
                        <path class="hs-password-active:hidden"
                            d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"></path>
                        <line class="hs-password-active:hidden" x1="2" x2="22" y1="2"
                            y2="22"></line>
                        <path class="hidden hs-password-active:block" d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z">
                        </path>
                        <circle class="hidden hs-password-active:block" cx="12" cy="12" r="3"></circle>
                    </svg>
                </button>
            </div>
            @error('password')
                <div class="flex flex-row gap-2">
                    <x-feathericon-info class="size-6 text-red-500"/>
                    <span class=" text-red-500">{{ $message }}</span>
                </div>
            @enderror
        </div>
        <div class="flex flex-col gap-2">
            <p class="text-lg">Confirm password</p>
            <div class="relative">
                <input wire:model.live.debounce.200ms="password_confirmation" id="hs-toggle-confirm-password" type="password"
                    class="py-2.5 sm:py-3 px-4 block w-full border border-black rounded-lg
                 sm:text-sm focus:border-blue-500 disabled:pointer-events-none
                  bg-white  text-[#000000CC] placeholder-[#000000B3]
                   focus:ring-neutral-600"
                    placeholder="Enter confirm password">
                <button type="button"
                    data-hs-toggle-password='{
        "target": "#hs-toggle-confirm-password"
      }'
                    class="absolute inset-y-0 end-0 flex items-center z-20 px-3 cursor-pointer text-gray-400 rounded-e-md focus:outline-hidden focus:text-black dark:text-neutral-600">
                    <svg class="shrink-0 size-3.5" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path class="hs-password-active:hidden" d="M9.88 9.88a3 3 0 1 0 4.24 4.24"></path>
                        <path class="hs-password-active:hidden"
                            d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"></path>
                        <path class="hs-password-active:hidden"
                            d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"></path>
                        <line class="hs-password-active:hidden" x1="2" x2="22" y1="2"
                            y2="22"></line>
                        <path class="hidden hs-password-active:block" d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z">
                        </path>
                        <circle class="hidden hs-password-active:block" cx="12" cy="12" r="3"></circle>
                    </svg>
                </button>
            </div>
            @error('password_confirmation')
                <div class="flex flex-row gap-2">
                    <x-feathericon-info class="size-6 text-red-500"/>
                    <span class=" text-red-500">{{ $message }}</span>
                </div>
            @enderror
        </div>
        <div>
            <button wire:click="signUp" type="button"
                class="w-full py-3 px-4 inline-flex items-center justify-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-black text-white  focus:outline-hidden  disabled:opacity-50 disabled:pointer-events-none">
                Sign up
            </button>
        </div>
    </div>
    <div class="text-black mt-4">
        <span>Already have an account?</span>
        <a class="text-[#00000099] cursor-default" wire:click.prevent="navigateToSignIn">Sign in</a>
    </div>
</div>
