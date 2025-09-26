<Tailwind CSS (было)>
<div class="w-80 rounded-lg bg-zinc-50 text-zinc-800">
  <div class="flex flex-col gap-3 p-8">
    <input placeholder="Email" class="w-full rounded-sm border border-zinc-300 bg-white px-3 py-2 text-sm focus:ring-2 focus:ring-zinc-700 focus:ring-offset-2 focus:ring-offset-zinc-100 focus:outline-none focus-visible:border-zinc-900"/>
    <label class="flex cursor-pointer items-center text-sm gap-1.5 text-zinc-500">
      <div class="relative inline-block h-5">
        <input type="checkbox" class="peer h-5 w-8 cursor-pointer appearance-none rounded-full border border-zinc-400 peer-checked:bg-white checked:border-zinc-900 focus-visible:ring-2 focus-visible:ring-zinc-400 checked:focus-visible:ring-zinc-900 focus-visible:ring-offset-2 focus-visible:outline-none"/>
        <span class="pointer-events-none absolute start-0.75 top-0.75 block size-[0.875rem] rounded-full bg-zinc-400 transition-all duration-200 peer-checked:start-[0.9375rem] peer-checked:bg-zinc-900"></span>
      </div>
      Подписаться на рассылку
    </label>
    <label class="flex cursor-pointer items-center text-sm gap-1.5 text-zinc-500">
      <div class="relative inline-block h-5">
        <input type="checkbox" class="peer h-5 w-8 cursor-pointer appearance-none rounded-full border border-zinc-400 peer-checked:bg-white checked:border-zinc-900 focus-visible:ring-2 focus-visible:ring-zinc-400 checked:focus-visible:ring-zinc-900 focus-visible:ring-offset-2 focus-visible:outline-none"/>
        <span class="pointer-events-none absolute start-0.75 top-0.75 block size-[0.875rem] rounded-full bg-zinc-400 transition-all duration-200 peer-checked:start-[0.9375rem] peer-checked:bg-zinc-900"></span>
      </div>
      Принять условия использования
    </label>
    <button class="inline-block cursor-pointer rounded-sm bg-zinc-900 px-4 py-2.5 text-center text-sm font-semibold text-white shadow-[0_.2rem_0.3rem_-.25rem_black] active:shadow-none transition duration-200 ease-in-out focus-visible:ring-2 focus-visible:ring-zinc-700 focus-visible:ring-offset-2 focus-visible:outline-none active:translate-y-[1px]" >Сохранить</button>
  </div>
</div>
</Tailwind CSS (было)>

---

<Tailwind CSS + daisyUI (стало)>
<div class="card w-80 bg-base-200">
  <div class="card-body gap-3">
    <input placeholder="Email" class="input" />
    <label class="label">
      <input type="checkbox" class="toggle toggle-sm" />
      Подписаться на рассылку
    </label>
    <label class="label">
      <input type="checkbox" class="toggle toggle-sm" />
      Принять условия использования
    </label>
    <button class="btn btn-neutral">Сохранить</button>
  </div>
</div>
</Tailwind CSS + daisyUI (стало)>