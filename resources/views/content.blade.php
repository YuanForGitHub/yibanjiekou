<main role="main" class="container bg-white">
    @if(isset($user))
    <p class="media-body pb-3 mb-0 small lh-125 border-bottom border-gray">
        <strong class="d-block text-gray-dark">你的id</strong>
        {{ $user['info']['yb_userid'] }}
    </p>
    <p class="media-body pb-3 mb-0 small lh-125 border-bottom border-gray">
        <strong class="d-block text-gray-dark">你的大学</strong>
        {{ $user['info']['yb_schoolname'] }}
    </p>
    <p class="media-body pb-3 mb-0 small lh-125 border-bottom border-gray">
            <strong class="d-block text-gray-dark">你的昵称</strong>
            {{ $user['info']['yb_usernick'] }}
        </p>
    <p class="media-body pb-3 mb-0 small lh-125 border-bottom border-gray">
    <strong class="d-block text-gray-dark">你的网薪</strong>
        {{ $user['info']['yb_money'] }}
    </p>
    <p class="media-body pb-3 mb-0 small lh-125 border-bottom border-gray">
        <strong class="d-block text-gray-dark">你的经验值</strong>
        {{ $user['info']['yb_exp'] }}
    </p>
    <p class="media-body pb-3 mb-0 small lh-125 border-bottom border-gray">
        <strong class="d-block text-gray-dark">你的头像</strong>
        <img src="{{ $user['info']['yb_userhead'] }}">
    </p>
    @endif
</main>