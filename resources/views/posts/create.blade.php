<x-app-layout>
    <div class="max-w-3xl mx-auto px-4 py-10">
        <div class="mb-6">
            <a href="{{ route('posts.index') }}" class="text-sm text-orange-600 hover:underline">← กลับไปหน้ากระทู้</a>
        </div>

        <div class="rounded-2xl bg-white ring-1 ring-black/5 shadow-sm overflow-hidden">
            <div class="px-6 md:px-8 py-6 border-b border-gray-100">
                <h1 class="text-2xl font-extrabold text-gray-900">ตั้งกระทู้ใหม่</h1>
                <p class="text-sm text-gray-500 mt-1">โพสต์ใหม่จะอยู่ในสถานะ <strong>รออนุมัติ</strong> โดยแอดมิน</p>
            </div>

            <div class="px-6 md:px-8 py-8">
                @if ($errors->any())
                    <div class="mb-5 rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-sm text-red-700">
                        กรุณาตรวจสอบข้อมูลที่กรอกให้ครบถ้วน
                    </div>
                @endif

                <form method="POST" action="{{ route('posts.store') }}" class="space-y-6">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium text-gray-700">หัวข้อ</label>
                        <input name="title"
                               value="{{ old('title') }}"
                               class="mt-1 w-full rounded-lg border-gray-300 focus:border-orange-400 focus:ring-2 focus:ring-orange-500/20"
                               placeholder="เช่น ขอคำแนะนำการใช้งาน ESP32">
                        <x-input-error :messages="$errors->get('title')" class="mt-2"/>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">เนื้อหา</label>
                        <textarea name="content" rows="10"
                                  class="mt-1 w-full rounded-lg border-gray-300 focus:border-orange-400 focus:ring-2 focus:ring-orange-500/20"
                                  placeholder="รายละเอียดคำถาม/แชร์ประสบการณ์...">{{ old('content') }}</textarea>
                        <x-input-error :messages="$errors->get('content')" class="mt-2"/>
                    </div>

                    <div class="flex items-center justify-end gap-3">
                        <a href="{{ route('posts.index') }}" class="text-sm text-gray-600 hover:text-gray-900">ยกเลิก</a>
                        <button class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-orange-500 text-white text-sm font-semibold hover:bg-orange-600">
                            ส่งกระทู้
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
