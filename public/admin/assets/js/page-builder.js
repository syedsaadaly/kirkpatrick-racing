document.addEventListener('DOMContentLoaded', function () {
    const cmsToggle = document.getElementById('is-cms');
    const iconSection = document.getElementById('icon-selection-section');
    const selectedIconInput = document.getElementById('selected-icon');
    const iconWrapper = document.getElementById('icon-scroll-wrapper');
    const savedIcon = selectedIconInput.value;

    const icons = [
        'fas fa-users', 'fas fa-user', 'fas fa-user-circle', 'fas fa-user-plus',
        'fas fa-briefcase', 'fas fa-tasks', 'fas fa-folder', 'fas fa-folder-open',
        'fas fa-shopping-cart', 'fas fa-box', 'fas fa-receipt', 'fas fa-truck',
        'fas fa-cog', 'fas fa-sliders-h', 'fas fa-wrench', 'fas fa-tools',
        'far fa-bell', 'fas fa-bell', 'far fa-envelope', 'fas fa-envelope',
        'fas fa-home', 'fas fa-chart-bar', 'fas fa-chart-line', 'fas fa-layer-group',
        'far fa-file', 'fas fa-file-alt', 'fas fa-file',
        'far fa-comment-dots', 'far fa-comment', 'far fa-comments', 'far fa-smile', 'far fa-frown-open',
        'far fa-grin', 'far fa-grin-beam', 'far fa-grin-beam-sweat', 'far fa-grin-hearts', 'far fa-grin-stars',
        'far fa-grin-wink', 'far fa-kiss', 'far fa-kiss-beam', 'far fa-kiss-wink-heart', 'far fa-laugh',
        'far fa-laugh-beam', 'far fa-laugh-squint', 'far fa-laugh-wink', 'far fa-meh', 'far fa-meh-blank',
        'far fa-meh-rolling-eyes', 'far fa-sad-cry', 'far fa-sad-tear', 'far fa-smile-beam', 'far fa-smile-wink',
        'far fa-surprise', 'far fa-tired', 'fas fa-comment-dots', 'fas fa-comment', 'fas fa-comments',
        'fas fa-smile', 'fas fa-frown-open', 'fas fa-grin', 'fas fa-grin-beam', 'fas fa-grin-beam-sweat',
        'fas fa-grin-hearts', 'fas fa-grin-stars', 'fas fa-grin-wink', 'fas fa-kiss', 'fas fa-kiss-beam',
        'fas fa-kiss-wink-heart', 'fas fa-laugh', 'fas fa-laugh-beam', 'fas fa-laugh-squint', 'fas fa-laugh-wink',
        'fas fa-meh', 'fas fa-meh-blank', 'fas fa-meh-rolling-eyes', 'fas fa-sad-cry', 'fas fa-sad-tear',
        'fas fa-smile-beam', 'fas fa-smile-wink', 'fas fa-surprise', 'fas fa-tired', 'fas fa-times',
        'fas fa-times-circle', 'fas fa-window-close', 'fas fa-chevron-down', 'fas fa-chevron-up',
        'fas fa-chevron-left', 'fas fa-chevron-right', 'fas fa-angle-down', 'fas fa-angle-up',
        'fas fa-angle-left', 'fas fa-angle-right', 'fas fa-caret-down', 'fas fa-caret-up',
        'fas fa-caret-left', 'fas fa-caret-right', 'fas fa-bars', 'fas fa-ellipsis-h',
        'fas fa-ellipsis-v', 'fas fa-plus', 'fas fa-minus', 'fas fa-expand', 'fas fa-compress',
        'fas fa-cog', 'fas fa-sliders-h', 'fas fa-user', 'fas fa-user-circle', 'fas fa-users',
        'fas fa-headset', 'fas fa-question-circle', 'fas fa-info-circle', 'fas fa-exclamation-circle',
        'fas fa-bell', 'fas fa-envelope', 'fas fa-paper-plane'
    ];

    if (savedIcon && icons.includes(savedIcon)) {
        icons.splice(icons.indexOf(savedIcon), 1);
        icons.unshift(savedIcon);
    }

    function generateIcons() {
        iconWrapper.innerHTML = '';

        icons.forEach(iconClass => {
            const iconElement = document.createElement('div');
            iconElement.className = 'icon-option';
            iconElement.dataset.icon = iconClass;

            iconElement.innerHTML = `
                <div class="icon-inner">
                    <i class="${iconClass} fa-lg"></i>
                </div>
            `;

            if (savedIcon && savedIcon === iconClass) {
                iconElement.classList.add('selected');
            }

            iconElement.addEventListener('click', function () {
                document.querySelectorAll('.icon-option').forEach(i => i.classList.remove('selected'));
                this.classList.add('selected');
                selectedIconInput.value = this.dataset.icon;
            });

            iconWrapper.appendChild(iconElement);
        });
    }

    cmsToggle.addEventListener('change', function () {
        if (this.checked) {
            iconSection.style.display = 'block';
            if (iconWrapper.children.length === 0) {
                generateIcons();
            }
        } else {
            iconSection.style.display = 'none';
            selectedIconInput.value = '';
            document.querySelectorAll('.icon-option').forEach(i => i.classList.remove('selected'));
        }
    });

    document.querySelector('.scroll-left')?.addEventListener('click', () => {
        iconWrapper.scrollBy({left: -200, behavior: 'smooth'});
    });
    document.querySelector('.scroll-right')?.addEventListener('click', () => {
        iconWrapper.scrollBy({left: 200, behavior: 'smooth'});
    });

    if (cmsToggle.checked) {
        generateIcons();
    }
});
