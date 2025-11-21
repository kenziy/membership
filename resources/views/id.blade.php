<div style="font-family: Inter, sans-serif; width: 420px; height: 260px; overflow: hidden; position: relative; background-color: #2e3133;">
    <div style="position: absolute; top: 0; left: 0; width: 150%; height: 150%; background: #ffd43b; transform: translate(-10%, 40%) rotate(-15deg); z-index: 1;"></div>
    <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; overflow: hidden; z-index: 2;">
        <div style="position: absolute; width: 120%; height: 4px; background-color: rgba(255, 255, 255, .1); top: 20px; right: -20px; transform: rotate(10deg);"></div>
        <div style="position: absolute; width: 100%; height: 15px; background-color: rgba(255, 255, 255, .05); top: 100px; left: 0; transform: rotate(-5deg);"></div>
        <div style="position: absolute; width: 150px; height: 150px; background-color: rgba(255, 255, 255, .1); clip-path: polygon(100% 0, 0 100%, 100% 100%); top: -50px; right: 0; transform: rotate(45deg);"></div>
    </div>
    <div style="position: relative; z-index: 3; padding: 20px; display: flex; flex-direction: column; height: 100%; color: #fff;">
        <div style="display: flex; align-items: center; margin-bottom: 20px;">
            <img src="data:image/svg+xml;base64,{{ base64_encode(url('/images/logo-white.svg')) }}" alt="Logo" />
        </div>
        <div style="display: flex; flex: 1; align-items: flex-start; justify-content: space-between;">
            <div style="margin-right: 20px; display: flex; align-items: center; justify-content: center;">
                <img src="{{ $user->profile_photo_path ? Storage::url($user->profile_photo_path) : 'https://placehold.co/80x80?text=' . substr($user->name, 0, 1) }}" style="width: 80px; height: 80px; border-radius: 1000px; border: 5px solid #fff;" alt="User Photo" />
            </div>
            <div style="flex-grow: 1; line-height: 1.1; position: relative; top: 10px; transform: translateX(10px);">
                <div style="font-size: 24px; font-weight: 900; text-transform: uppercase; text-shadow: 1px 1px 6px #000;">{{ $user->name }}</div>
                <div style="font-size: 14px; font-weight: 500; margin-top: 5px; text-shadow: 1px 1px 6px #000;">{{ $user->member_id }}</div>
                @if($user->isVIP())
                    <div style="margin-top: 10px; padding: 4px 12px; border-radius: 16px; background: linear-gradient(180deg, #ffd43b 0, #ffa800 100%); color: #554000; font-weight: 800; font-size: 12px; box-shadow: 0 2px 5px rgba(0, 0, 0, .2); display: inline-block; align-items: center;">
                        <svg fill="currentColor" height="14" style="margin-right: 5px; color: #554000;" viewBox="0 0 24 24" width="14">
                            <path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z"/>
                        </svg>
                        VIP MEMBER
                    </div>
                @endif
            </div>
            <img src="{{ $user->qr_code_path ? Storage::url($user->qr_code_path) : 'https://placehold.co/80x80?text=QR%20Code%0A%20Not%20Available' }}" style="position: absolute; bottom: 0; right: 20px; bottom: 60px; z-index: 4; width: 80px; height: 80px;" alt="QR Code" />
        </div>
    </div>
</div>
