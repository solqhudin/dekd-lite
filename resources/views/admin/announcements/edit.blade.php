<x-app-layout>
    <div class="max-w-3xl mx-auto px-4 py-10">
        <div class="rounded-2xl bg-white ring-1 ring-black/5 shadow-sm overflow-hidden">
            <div class="px-6 md:px-8 py-6 border-b border-gray-100">
                <h1 class="text-2xl font-extrabold text-gray-900">แก้ไขประกาศ</h1>
            </div>

            <div class="px-6 md:px-8 py-8">
                @if ($errors->any())
                    <div class="mb-5 rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-sm text-red-700">
                        กรุณาตรวจสอบข้อมูลที่กรอก
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.announcements.update', $announcement) }}" enctype="multipart/form-data" class="grid gap-5">
                    @csrf @method('PUT')

                    <div>
                        <label class="block text-sm font-medium text-gray-700">หัวข้อ</label>
                        <input name="title" value="{{ old('title', $announcement->title) }}" class="mt-1 w-full rounded-lg border-gray-300">
                    </div>

                    <div class="grid sm:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Slug (ไม่บังคับ)</label>
                            <input name="slug" value="{{ old('slug', $announcement->slug) }}" class="mt-1 w-full rounded-lg border-gray-300">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">หมวด</label>
                            <input name="category" value="{{ old('category', $announcement->category) }}" class="mt-1 w-full rounded-lg border-gray-300">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">คำโปรย (Excerpt)</label>
                        <textarea name="excerpt" rows="2" class="mt-1 w-full rounded-lg border-gray-300">{{ old('excerpt', $announcement->excerpt) }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">เนื้อหา (Body) รองรับ HTML</label>
                        <textarea name="body" rows="8" class="mt-1 w-full rounded-lg border-gray-300">{{ old('body', $announcement->body) }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">รูปหน้าปก (ถ้ามี)</label>
                        <input type="file" name="cover_image" class="mt-1 block w-full text-sm">
                        @if($announcement->cover_image)
                            <img src="{{ asset('storage/'.$announcement->cover_image) }}" class="mt-2 rounded-lg h-24 object-cover" alt="">
                        @endif
                    </div>

                    <div class="grid sm:grid-cols-2 gap-5">
                        <label class="inline-flex items-center gap-2 text-sm">
                            <input type="checkbox" name="is_published" value="1" {{ old('is_published', $announcement->is_published) ? 'checked' : '' }}>
                            เผยแพร่
                        </label>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">เวลาที่เผยแพร่ (ถ้ากำหนด)</label>
                            <input type="datetime-local" name="published_at"
                                   value="{{ old('published_at', optional($announcement->published_at)->format('Y-m-d\TH:i')) }}"
                                   class="mt-1 w-full rounded-lg border-gray-300">
                        </div>
                    </div>

                    <div class="pt-2">
                        <button class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-orange-500 text-white text-sm font-semibold hover:bg-orange-600">
                            บันทึกการแก้ไข
                        </button>
                        <a href="{{ route('admin.announcements.index') }}" class="ml-3 text-sm text-gray-600 hover:text-gray-900">ยกเลิก</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
