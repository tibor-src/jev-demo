const demoNode = document.querySelector('#jev-demo');
let token = '';
let language = 'php';

if (demoNode !== null) {
    const demo = JSON.parse(demoNode.textContent);
    token = document.querySelector('meta[name="csrf-token"]').content;
    const refreshRequests = [];

    document.querySelectorAll('[data-lang]').forEach((button) => {
        button.addEventListener('click', () => {
            language = button.dataset.lang;

            document.querySelectorAll('[data-lang]').forEach((item) => {
                const active = item.dataset.lang === language;
                item.setAttribute('aria-pressed', active ? 'true' : 'false');
                item.className = active
                    ? 'rounded-md bg-[#f53003] px-2 py-1 font-medium text-white dark:bg-[#FF4433]'
                    : 'rounded-md border border-[#e3e3e0] px-2 py-1 font-medium dark:border-[#3E3E3A]';
            });

            refreshRequests.forEach((refresh) => refresh());
        });
    });

    document.querySelectorAll('[data-jev]').forEach((section) => {
        const type = section.dataset.jev;
        const question = demo.questions[type];
        const select = section.querySelector('select');
        const button = section.querySelector('button');
        const before = section.querySelector('[data-before]');
        const running = section.querySelector('[data-running]');
        const after = section.querySelector('[data-after]');
        const request = section.querySelector('[data-request]');
        const response = section.querySelector('[data-response]');
        const input = section.querySelector('[data-input]');

        const selected = () => question.scenarios[Number(select.value)];

        const showRequest = () => {
            request.textContent = requestCode(type, question, selected().text);
        };

        const showInput = () => {
            input.textContent = selected().text;
        };

        const reset = () => {
            before.hidden = false;
            running.hidden = true;
            after.hidden = true;
            after.innerHTML = '';
            response.textContent = 'Response appears after you run this question.';
            button.disabled = false;
            button.textContent = 'Run';
            showInput();
            showRequest();
        };

        select.addEventListener('change', reset);
        button.addEventListener('click', () => run(type, select, button, before, running, after, response));
        refreshRequests.push(showRequest);
        showRequest();
    });
}

async function run(type, select, button, before, running, after, response) {
    const started = performance.now();

    button.disabled = true;
    button.textContent = 'Running…';
    before.hidden = true;
    after.hidden = true;
    running.hidden = false;

    let data;

    try {
        const result = await fetch(`/${type}`, {
            method: 'POST',
            headers: {
                Accept: 'application/json',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token,
            },
            body: JSON.stringify({ scenario: Number(select.value) }),
        });
        const payload = await result.json();

        if (!result.ok || !payload.data) {
            throw new Error(payload.message || 'Jev did not return an answer.');
        }

        data = payload.data;
    } catch (error) {
        running.hidden = true;
        before.hidden = false;
        response.textContent = error.message;
        button.disabled = false;
        button.textContent = 'Run';

        return;
    }

    const remaining = 700 - (performance.now() - started);

    if (remaining > 0) {
        await new Promise((resolve) => {
            setTimeout(resolve, remaining);
        });
    }

    response.textContent = JSON.stringify(data, null, 4);
    after.innerHTML = renderAnswer(type, data);
    running.hidden = true;
    after.hidden = false;
    button.disabled = false;
    button.textContent = 'Run';
}

function renderAnswer(type, data) {
    if (type === 'boolean') {
        const percent = (data.probability * 100).toFixed(1);
        const yes = data.probability > 0.8 ? 'yes' : 'no';

        return `
            <p class="text-4xl font-medium tabular-nums">${percent}%</p>
            <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">isTrue(0.8): ${yes}</p>
            <div class="h-2 overflow-hidden rounded-full bg-[#e3e3e0] dark:bg-[#3E3E3A]">
                <div class="h-2 rounded-full bg-[#f53003] dark:bg-[#FF4433]" style="width: ${Math.min(100, Math.max(0, data.probability * 100))}%"></div>
            </div>
        `;
    }

    if (type === 'choice') {
        const confidence = data.confidence === null || data.confidence === undefined
            ? ''
            : ` · confidence ${(data.confidence * 100).toFixed(1)}%`;
        const bars = Object.entries(data.probabilities).map(([option, probability]) => {
            const chosen = option === data.choice;
            const bar = chosen ? 'bg-[#f53003] dark:bg-[#FF4433]' : 'bg-[#1b1b18] dark:bg-[#EDEDEC]';

            return `
                <div class="flex flex-col gap-1">
                    <div class="flex items-baseline justify-between gap-3 text-sm">
                        <span class="${chosen ? 'font-medium' : ''}">${escapeHtml(option)}</span>
                        <span class="tabular-nums text-[#706f6c] dark:text-[#A1A09A]">${(probability * 100).toFixed(1)}%</span>
                    </div>
                    <div class="h-2 overflow-hidden rounded-full bg-[#e3e3e0] dark:bg-[#3E3E3A]">
                        <div class="h-2 rounded-full ${bar}" style="width: ${Math.min(100, Math.max(0, probability * 100))}%"></div>
                    </div>
                </div>
            `;
        }).join('');

        return `
            <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">Choice <span class="font-medium text-[#1b1b18] dark:text-[#EDEDEC]">${escapeHtml(data.choice)}</span>${confidence}</p>
            <div class="flex flex-col gap-3">${bars}</div>
        `;
    }

    const legend = data.legend ?? [];
    const probabilities = data.probabilities ?? [];
    const maxIndex = Math.max(legend.length - 1, 1);
    const score = Number(data.score);
    const position = Number.isFinite(score) ? Math.min(100, Math.max(0, (score / maxIndex) * 100)) : 0;
    const nearest = Number.isFinite(score) ? Math.min(legend.length - 1, Math.max(0, Math.round(score))) : 0;
    const lower = Number.isFinite(score) ? Math.min(legend.length - 1, Math.max(0, Math.floor(score))) : 0;
    const upper = Number.isFinite(score) ? Math.min(legend.length - 1, Math.max(0, Math.ceil(score))) : 0;
    const onLevel = !Number.isFinite(score) || Math.abs(score - Math.round(score)) < 0.05 || lower === upper;
    const levelName = (index) => escapeHtml(legend[index] ?? 'unknown');
    const headline = onLevel ? levelName(nearest) : `Between ${levelName(lower)} and ${levelName(upper)}`;
    const scoreLabel = Number.isFinite(score) ? score.toFixed(1) : escapeHtml(String(data.score));
    const confidence = data.confidence === null || data.confidence === undefined
        ? ''
        : ` · confidence ${(data.confidence * 100).toFixed(1)}%`;
    const markerAlign = position < 15
        ? 'left-0'
        : (position > 85 ? 'right-0' : 'left-1/2 -translate-x-1/2');
    const stops = legend.map((label, index) => {
        const align = index === 0
            ? 'items-start text-left'
            : (index === legend.length - 1 ? 'items-end text-right' : 'items-center text-center');
        const emphasized = onLevel ? index === nearest : index === lower || index === upper;
        const percent = ((probabilities[index] ?? 0) * 100).toFixed(1);

        return `
            <div class="flex min-w-0 flex-col gap-1 ${align} ${emphasized ? 'font-medium text-[#1b1b18] dark:text-[#EDEDEC]' : ''}">
                <span class="tabular-nums text-[#706f6c] dark:text-[#A1A09A]">${index}</span>
                <span>${escapeHtml(label)}</span>
                <span class="font-normal tabular-nums text-[#706f6c] dark:text-[#A1A09A]">${percent}%</span>
            </div>
        `;
    }).join('');

    return `
        <p class="text-xl font-medium">${headline}</p>
        <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">Score ${scoreLabel} of ${maxIndex}${confidence}</p>
        <div class="relative mt-7 h-2 rounded-full bg-[#e3e3e0] dark:bg-[#3E3E3A]">
            <div class="absolute inset-y-0 left-0 rounded-full bg-[#f53003] dark:bg-[#FF4433]" style="width: ${position}%"></div>
            <div class="absolute top-1/2 z-10 -translate-y-1/2" style="left: ${position}%">
                <span class="absolute bottom-4 ${markerAlign} whitespace-nowrap text-xs font-medium tabular-nums">${scoreLabel}</span>
                <span class="block size-4 -translate-x-1/2 rounded-full border-2 border-white bg-[#f53003] dark:border-[#161615] dark:bg-[#FF4433]"></span>
            </div>
        </div>
        <div class="grid gap-3 text-sm" style="grid-template-columns: repeat(${Math.max(legend.length, 1)}, minmax(0, 1fr))">${stops}</div>
    `;
}

function requestCode(type, question, text) {
    return language === 'js' ? jsRequest(type, question, text) : phpRequest(type, question, text);
}

function phpRequest(type, question, text) {
    const className = type.charAt(0).toUpperCase() + type.slice(1);
    const body = type === 'boolean'
        ? criteriaCode(question.question.criteria)
        : (type === 'choice' ? optionsCode(question.question.options) : levelsCode(question.question.levels));

    return `use Laravel\\Ai\\Classification;
use Laravel\\Ai\\Classification\\${className};

$response = Classification::of(${phpString(text)})
    ->question(${phpString(type)}, new ${className}(${phpString(question.question.instructions)}, [
${body}
    ]))
    ->classify();

$response->answer(${phpString(type)})${type === 'boolean' ? '->isTrue(0.8)' : ''};`;
}

function jsRequest(type, question, text) {
    const helper = type === 'boolean' ? 'noul' : type;
    const body = type === 'boolean'
        ? jsCriteria(question.question.criteria)
        : (type === 'choice' ? jsOptions(question.question.options) : jsLevels(question.question.levels));
    const read = type === 'boolean'
        ? 'response.answers.boolean.noul > 0.8;'
        : `response.answers.${type};`;

    return `import { TypeSafeClient, ${helper} } from '@typesafe-ai/sdk';

const client = new TypeSafeClient();

const response = await client.systemOne({
    state: ${phpString(text)},
    questions: {
        ${type}: ${helper}(${phpString(question.question.instructions)}, ${type === 'score' ? '[' : '{'}
${body}
        ${type === 'score' ? ']' : '}'}),
    },
});

${read}`;
}

function jsCriteria(criteria) {
    return `            true: ${phpString(criteria.true)},
            false: ${phpString(criteria.false)},`;
}

function jsOptions(options) {
    return Object.entries(options).map(([key, description]) => `            ${key}: ${phpString(description)},`).join('\n');
}

function jsLevels(levels) {
    return levels.map((level) => `            ${phpString(level)},`).join('\n');
}

function criteriaCode(criteria) {
    return `        'true' => ${phpString(criteria.true)},
        'false' => ${phpString(criteria.false)},`;
}

function optionsCode(options) {
    return Object.entries(options).map(([key, description]) => `        ${phpString(key)} => ${phpString(description)},`).join('\n');
}

function levelsCode(levels) {
    return levels.map((level) => `        ${phpString(level)},`).join('\n');
}

function phpString(value) {
    return `'${String(value).replace(/\\/g, '\\\\').replace(/'/g, "\\'")}'`;
}

function escapeHtml(value) {
    return String(value).replace(/[&<>"']/g, (character) => ({
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#39;',
    })[character]);
}
