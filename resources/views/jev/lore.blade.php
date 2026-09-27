@php($page = \App\Jev\Pages::page('lore'))
<x-layouts.site
    :title="$page['title']"
    :description="$page['description']"
    :canonical="$page['url']"
    :og-type="$page['og']"
    schema-key="lore"
>
    <article class="flex max-w-xl flex-col gap-8">
        <header class="flex flex-col gap-2">
            <h2 class="text-xl font-medium tracking-tight">Lore</h2>
            <p class="text-[#706f6c] dark:text-[#A1A09A]">
                Two borrowed names, and the reason TypeSafe says they fit. This is a reading of their launch post, not a page they wrote.
            </p>
        </header>

        <section id="system-one" class="flex flex-col gap-3">
            <h3 class="text-lg font-medium">System One</h3>
            <p class="text-sm leading-6 text-[#706f6c] dark:text-[#A1A09A]">
                The model class is named from Daniel Kahneman’s <span class="text-[#1b1b18] dark:text-[#EDEDEC]">Thinking, Fast and Slow</span>. In that book, System 1 is fast and intuitive. System 2 is slower and more deliberate. A chat model spends its effort producing language, which is closer to the slow mode: a sequence of words, each one depending on the last. TypeSafe wanted the other mode for software. A judgment you can use immediately, already in the shape the program expects.
            </p>
            <p class="text-sm leading-6 text-[#706f6c] dark:text-[#A1A09A]">
                They also note the insult that usually comes with the name. “System 1” has meant the sloppy one, the bias, the answer you trust too quickly. Almeida writes that they think a System One model can be made more reliable than the alternative, and that they will argue that later. The published part of the argument is narrower: the answers are calibrated, the options are closed, and the model does not generate a string that then has to be hoped into a type.
            </p>
        </section>

        <section id="jevons" class="flex flex-col gap-3">
            <h3 class="text-lg font-medium">Jev</h3>
            <p class="text-sm leading-6 text-[#706f6c] dark:text-[#A1A09A]">
                The model is named for William Stanley Jevons, the nineteenth-century economist. In 1865 he wrote about coal. Better steam engines used less coal to do the same work. The comfortable prediction was that Britain would burn less. Jevons argued the opposite. A cheaper unit of work makes work worth doing in more places, so total consumption rises. That observation is now called the Jevons paradox.
            </p>
            <p class="text-sm leading-6 text-[#706f6c] dark:text-[#A1A09A]">
                TypeSafe expects machine intelligence to follow the coal. Their line, from the same post: every order of magnitude drop in the cost of intelligence unlocks orders of magnitude more use cases. A judgment that is cheap enough stops being a feature you add to a chatbot. It becomes something you call inside a loop, a queue, a page, a guard in front of another model. The name is a bet on demand, not a claim that the model is an economist.
            </p>
        </section>

        <section id="missing-automation" class="flex flex-col gap-3">
            <h3 class="text-lg font-medium">The missing automation</h3>
            <p class="text-sm leading-6 text-[#706f6c] dark:text-[#A1A09A]">
                Almeida’s opening question is why the automation did not follow the chat. He had worked on the methods that made language models useful at following instructions and talking with people, the research that ended up behind ChatGPT. Models became unusually good at conversation. Software that needed a decision still had to coax a text generator into behaving like a function. TypeSafe spent two years in stealth on a stack aimed at that job: a different architecture, a parallel sampler, and a training method they call reinforcement learning for calibrated decisions.
            </p>
            <p class="text-sm leading-6 text-[#706f6c] dark:text-[#A1A09A]">
                Jev shipped in early access on <time datetime="2026-09-15">15 September 2026</time>. The sentence they want remembered is the function-call one. Unstructured state in. Typed probabilistic decisions out. The rest of this site is that sentence, made runnable: the <a href="{{ route('jev.create') }}" class="text-[#1b1b18] underline dark:text-[#EDEDEC]">Demo</a> page, and the <a href="https://typesafe.ai/blog/introducing-system-one-models-and-jev" class="text-[#1b1b18] underline dark:text-[#EDEDEC]">post</a> if you want their version rather than this one.
            </p>
        </section>
    </article>
</x-layouts.site>
