@extends('layouts.lk')

@section("lk-content")
    <h1 class="display text-3xl md:text-4xl">Редактировать запись</h1>
    <p class="mt-3 max-w-lg text-white/65">
        Измени тренера, время или описание тренировки.
    </p>

    <form method="POST"
          action="/trainings/edit/{{ $training->id }}"
          enctype="multipart/form-data"
          class="mt-10 flex max-w-2xl flex-col gap-6">
        @csrf
        @method('PATCH')

        @if ($errors->any())
            <p role="alert" class="rounded-lg border border-error/40 px-4 py-3 text-sm font-medium text-error">
                Проверь поля, отмеченные красным.
            </p>
        @endif

        {{-- ФОТОГРАФИЯ --}}
        <div class="flex flex-col gap-2">
            <label for="photo" class="text-sm font-semibold">
                Фотография (по желанию)
            </label>

            <label id="photo-dropzone" for="photo"
                   class="group flex cursor-pointer flex-col items-center justify-center gap-3 rounded-xl border border-dashed border-white/20 bg-brand-card px-6 py-8 text-center transition hover:border-brand-red hover:bg-white/[0.03]">

                <div id="photo-placeholder"
                     class="{{ $training->image_url ? 'hidden' : 'flex' }} flex-col items-center gap-3">
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
                     src="{{ $training->image_url ?: '' }}"
                     alt="Предпросмотр фотографии"
                     class="{{ $training->image_url ? '' : 'hidden' }} max-h-80 w-full rounded-lg object-contain">

                <p id="photo-filename" class="hidden break-all text-sm text-white/65"></p>

                <input id="photo" name="image" type="file"
                       accept="image/jpeg,image/png,image/webp"
                       class="sr-only">
            </label>

            <input type="hidden" id="remove-image" name="remove_image" value="0">

            <button id="photo-remove" type="button"
                    class="{{ $training->image_url ? '' : 'hidden' }} self-start text-sm font-medium text-error hover:opacity-80">
                Удалить фотографию
            </button>

            <p class="text-sm text-white/45">
                {{ $training->image_url ? 'Текущая фотография. Выбери другую, чтобы заменить её.' : 'Можно прикрепить изображение к записи о тренировке.' }}
            </p>
        </div>

        {{-- ТРЕНЕР --}}
        <div class="flex flex-col gap-2">
            <label for="trainer_id" class="text-sm font-semibold">Тренер</label>

            <div class="relative">
                <select id="trainer_id" name="trainer_id"
                        @error('trainer_id') aria-invalid="true" aria-describedby="trainer_id-error" @enderror
                        class="w-full appearance-none rounded-lg border border-white/15 bg-brand-card py-3 pl-4 pr-11 text-base focus:border-brand-red focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-red aria-invalid:border-error">
                    <option value="" disabled>Выбери тренера</option>

                    @foreach ($trainers as $trainer)
                        <option value="{{ $trainer->id }}"
                            @selected(old('trainer_id', $training->trainer_id) == $trainer->id)>
                            {{ $trainer->name }} {{ $trainer->surname }}
                        </option>
                    @endforeach
                </select>

                <svg class="pointer-events-none absolute right-4 top-1/2 h-4 w-4 -translate-y-1/2 text-white/55"
                     viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd"/>
                </svg>
            </div>

            @error('trainer_id')
            <p id="trainer_id-error" role="alert" class="text-sm font-medium text-error">
                {{ $message }}
            </p>
            @enderror
        </div>

        {{-- ВРЕМЯ --}}
        <div class="flex flex-col gap-6 sm:flex-row">
            <x-field name="start_at" label="Начало" type="datetime-local"
                     :value="old('start_at', \Carbon\Carbon::parse($training->start_at)->format('Y-m-d\TH:i'))" />

            <x-field name="end_at" label="Конец" type="datetime-local"
                     :value="old('end_at', \Carbon\Carbon::parse($training->end_at)->format('Y-m-d\TH:i'))" />
        </div>

        {{-- ТЕМА --}}
        <x-field name="name" label="Тема тренировки (по желанию)"
                 :value="old('name', $training->name)" />

        <x-field name="cost" label="Цена тренировки (по умолчанию 400₽)" placeholder="400 ₽"  :value="old('cost', $training->cost)" />

        {{-- ПОЖЕЛАНИЯ --}}
        <div class="flex flex-col gap-2">
            <label for="description" class="text-sm font-semibold">
                Пожелания (по желанию)
            </label>

            <textarea id="description" name="description" rows="4" maxlength="255"
                      @error('description') aria-invalid="true" aria-describedby="description-error" @enderror
                      class="resize-y rounded-lg border border-white/15 bg-brand-card px-4 py-3 text-base placeholder:text-white/30 focus:border-brand-red focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-red aria-invalid:border-error">{{ old('description', $training->description) }}</textarea>

            <p class="text-sm text-white/45">До 255 символов.</p>

            @error('description')
            <p id="description-error" role="alert" class="text-sm font-medium text-error">
                {{ $message }}
            </p>
            @enderror
        </div>

        <div class="flex flex-wrap items-center gap-4">
            <button type="submit"
                    class="rounded-full bg-brand-red px-8 py-3 font-semibold hover:bg-brand-dark">
                Сохранить изменения
            </button>

            <a href="/profile"
               class="rounded-full border border-white/30 px-8 py-3 font-semibold hover:border-white">
                Отмена
            </a>
        </div>
    </form>

    <script>
        (() => {
            const input = document.getElementById('photo');
            const dropzone = document.getElementById('photo-dropzone');
            const placeholder = document.getElementById('photo-placeholder');
            const preview = document.getElementById('photo-preview');
            const filename = document.getElementById('photo-filename');
            const removeButton = document.getElementById('photo-remove');
            const removeImage = document.getElementById('remove-image');

            let previewUrl = null;
            let originalImage = preview.getAttribute('src');

            function renderPreview(file) {
                if (previewUrl) {
                    URL.revokeObjectURL(previewUrl);
                    previewUrl = null;
                }

                if (!file) {
                    filename.textContent = '';
                    filename.classList.add('hidden');

                    if (removeImage.value === '1' || !originalImage) {
                        preview.removeAttribute('src');
                        preview.classList.add('hidden');
                        placeholder.classList.remove('hidden');
                        removeButton.classList.add('hidden');
                    } else {
                        preview.src = originalImage;
                        preview.classList.remove('hidden');
                        placeholder.classList.add('hidden');
                        removeButton.classList.remove('hidden');
                    }

                    return;
                }

                if (!['image/jpeg', 'image/png', 'image/webp'].includes(file.type)) {
                    input.value = '';
                    renderPreview(null);
                    return;
                }

                previewUrl = URL.createObjectURL(file);
                preview.src = previewUrl;
                preview.classList.remove('hidden');
                placeholder.classList.add('hidden');

                filename.textContent = file.name;
                filename.classList.remove('hidden');
                removeButton.classList.remove('hidden');

                removeImage.value = '0';
            }

            input.addEventListener('change', () => {
                renderPreview(input.files[0] || null);
            });

            removeButton.addEventListener('click', () => {
                input.value = '';
                removeImage.value = '1';
                originalImage = '';
                renderPreview(null);
                removeButton.classList.add('hidden');
            });

            dropzone.addEventListener('dragover', event => {
                event.preventDefault();
                dropzone.classList.add('border-brand-red');
            });

            dropzone.addEventListener('dragleave', () => {
                dropzone.classList.remove('border-brand-red');
            });

            dropzone.addEventListener('drop', event => {
                event.preventDefault();
                dropzone.classList.remove('border-brand-red');

                const file = event.dataTransfer.files[0];

                if (file && ['image/jpeg', 'image/png', 'image/webp'].includes(file.type)) {
                    const transfer = new DataTransfer();
                    transfer.items.add(file);
                    input.files = transfer.files;
                    renderPreview(file);
                }
            });
        })();
    </script>
@endsection
