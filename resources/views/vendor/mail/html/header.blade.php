@props(['url'])
<tr>
    <td class="header">
        <a href="{{ $url }}" style="display: inline-flex; align-items: center; gap: 10px; text-decoration: none;">
            
            {{-- OPTION 1: Use a Public URL (Works on Localhost & Gmail immediately) --}}
            <img src="https://cdn-icons-png.flaticon.com/512/3448/3448339.png" width="38" alt="BusPH Logo" style="vertical-align: middle;">

            {{-- OPTION 2: Use your local file (Will only work when you deploy your site live) --}}
            {{-- <img src="{{ asset('images/logo.png') }}" width="38" alt="BusPH Logo" style="vertical-align: middle;"> --}}

            <span style="color: #001233; font-size: 28px; font-weight: 900; letter-spacing: -0.5px; vertical-align: middle;">BusPH</span>
        </a>
    </td>
</tr>