@extends('layouts.lk')

@section("lk-content")
    <h1 class="display text-3xl md:text-4xl">Создать запись</h1>
    <p class="mt-3 max-w-lg text-white/65">Выбери тренера и время. Запись появится в разделе «Мои записи».</p>

    <form method="POST" action="/trainings/create" class="mt-10 flex max-w-2xl flex-col gap-6" enctype="multipart/form-data">
        @csrf

        @if ($errors->any())
            <p role="alert" class="rounded-lg border border-error/40 px-4 py-3 text-sm font-medium text-error">
                Проверь поля, отмеченные красным.
            </p>
        @endif

        <div class="flex flex-col gap-2">
            <label for="photo" class="text-sm font-semibold">
                Фотография (по желанию)
            </label>

            <label id="photo-dropzone" for="photo"
                   class="group flex cursor-pointer flex-col items-center justify-center gap-3 rounded-xl border border-dashed border-white/20 bg-brand-card px-6 py-8 text-center transition hover:border-brand-red hover:bg-white/[0.03]">

                <div id="photo-placeholder" class="flex flex-col items-center gap-3">
                    <div class="flex h-12 w-12 items-center justify-center rounded-full bg-white/[0.06] text-white/65 transition group-hover:text-brand-red">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="1.5"
                             stroke-linecap="round" stroke-linejoin="round"
                             aria-hidden="true">
                            <rect x="3" y="3" width="18" height="18" rx="3"/>
                            <circle cx="8.5" cy="8.5" r="1.5"/>
                            <path d="m21 15-5-5L5 21"/>
                        </svg>
                    </div>

                    <div>
                        <p class="font-semibold">
                            <span class="text-brand-red">Выбрать фотографию</span>
                            или перетащить сюда
                        </p>
                        <p class="mt-1 text-sm text-white/45">
                            JPG, PNG или WEBP
                        </p>
                    </div>
                </div>

                <img id="photo-preview"
                     class="hidden max-h-80 w-full rounded-lg object-contain"
                     alt="Предпросмотр фотографии">

                <p id="photo-filename" class="hidden break-all text-sm text-white/65"></p>

                <input id="photo" name="image" type="file"
                       accept="image/jpeg,image/png,image/webp"
                       class="sr-only">
            </label>

            <button id="photo-remove" type="button"
                    class="hidden self-start text-sm font-medium text-error hover:opacity-80">
                Удалить фотографию
            </button>

            <p class="text-sm text-white/45">
                Можно прикрепить изображение к записи о тренировке.
            </p>
        </div>

        <script>
            (() => {
                const input = document.getElementById('photo');
                const dropzone = document.getElementById('photo-dropzone');
                const placeholder = document.getElementById('photo-placeholder');
                const preview = document.getElementById('photo-preview');
                const filename = document.getElementById('photo-filename');
                const removeButton = document.getElementById('photo-remove');

                let previewUrl = null;

                function renderPreview(file) {
                    if (previewUrl) {
                        URL.revokeObjectURL(previewUrl);
                        previewUrl = null;
                    }

                    if (!file) {
                        preview.removeAttribute('src');
                        preview.classList.add('hidden');
                        filename.textContent = '';
                        filename.classList.add('hidden');
                        placeholder.classList.remove('hidden');
                        removeButton.classList.add('hidden');
                        return;
                    }

                    if (!file.type.startsWith('image/')) {
                        input.value = '';
                        renderPreview(null);
                        return;
                    }

                    previewUrl = URL.createObjectURL(file);
                    preview.src = previewUrl;
                    preview.classList.remove('hidden');

                    filename.textContent = file.name;
                    filename.classList.remove('hidden');

                    placeholder.classList.add('hidden');
                    removeButton.classList.remove('hidden');
                }

                input.addEventListener('change', () => {
                    renderPreview(input.files[0]);
                });

                removeButton.addEventListener('click', () => {
                    input.value = '';
                    renderPreview(null);
                });

                dropzone.addEventListener('dragover', (event) => {
                    event.preventDefault();
                    dropzone.classList.add('border-brand-red');
                });

                dropzone.addEventListener('dragleave', () => {
                    dropzone.classList.remove('border-brand-red');
                });

                dropzone.addEventListener('drop', (event) => {
                    event.preventDefault();
                    dropzone.classList.remove('border-brand-red');

                    const file = event.dataTransfer.files[0];

                    if (file && file.type.startsWith('image/')) {
                        const transfer = new DataTransfer();
                        transfer.items.add(file);
                        input.files = transfer.files;
                        renderPreview(file);
                    }
                });
            })();
        </script>

        {{-- ТРЕНЕР --}}
        <div class="flex flex-col gap-2">
            <label for="trainer_id" class="text-sm font-semibold">Тренер</label>
            <div class="relative">
                <select id="trainer_id" name="trainer_id"
                        @error('trainer_id') aria-invalid="true" aria-describedby="trainer_id-error" @enderror
                        class="w-full appearance-none rounded-lg border border-white/15 bg-brand-card py-3 pl-4 pr-11 text-base focus:border-brand-red focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-red aria-invalid:border-error">
                    <option value="" disabled @selected(! old('trainer_id'))>Выбери тренера</option>
                    @foreach ($trainers as $trainer)
                        <option value="{{ $trainer->id }}" @selected(old('trainer_id') == $trainer->id)>
                            {{ $trainer->name }} {{ $trainer->surname }}
                        </option>
                    @endforeach
                </select>
                <svg class="pointer-events-none absolute right-4 top-1/2 h-4 w-4 -translate-y-1/2 text-white/55" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd"/>
                </svg>
            </div>
            @error('trainer_id')
            <p id="trainer_id-error" role="alert" class="flex items-start gap-2 text-sm font-medium text-error">
                <svg class="mt-0.5 h-4 w-4 shrink-0" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-4a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 6zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
                </svg>
                <span>{{ $message }}</span>
            </p>
            @enderror
        </div>

        {{-- ВРЕМЯ --}}
        <div class="flex flex-col gap-6 sm:flex-row">
            <x-field name="start_at" label="Начало" type="datetime-local" />
            <x-field name="end_at" label="Конец" type="datetime-local" />
        </div>

        {{-- ТЕМА --}}
        <x-field name="name" label="Тема тренировки (по желанию)" />

        <x-field name="cost" label="Цена тренировки (по умолчанию 400₽)" placeholder="400 ₽" />

        {{-- ПОЖЕЛАНИЯ --}}
        <div class="flex flex-col gap-2">
            <label for="description" class="text-sm font-semibold">Пожелания (по желанию)</label>
            <textarea id="description" name="description" rows="4" maxlength="255"
                      @error('description') aria-invalid="true" aria-describedby="description-error" @enderror
                      class="resize-y rounded-lg border border-white/15 bg-brand-card px-4 py-3 text-base placeholder:text-white/30 focus:border-brand-red focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-red aria-invalid:border-error">{{ old('description') }}</textarea>
            <p class="text-sm text-white/45">До 255 символов.</p>
            @error('description')
            <p id="description-error" role="alert" class="flex items-start gap-2 text-sm font-medium text-error">
                <svg class="mt-0.5 h-4 w-4 shrink-0" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-4a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 6zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
                </svg>
                <span>{{ $message }}</span>
            </p>
            @enderror
        </div>

        <div class="flex flex-wrap items-center gap-4">
            <button type="submit" class="rounded-full bg-brand-red px-8 py-3 font-semibold hover:bg-brand-dark">
                Создать запись
            </button>
            <a href="/profile" class="rounded-full border border-white/30 px-8 py-3 font-semibold hover:border-white">
                Отмена
            </a>
        </div>
    </form>
@endsection
