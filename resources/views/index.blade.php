<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>

    <header class="bg-dark py-3">
        <div class="inner">
            <h1 class="text-white">業務打刻ツール</h1>
        </div>
    </header>

    <body>
        <div class=inner>
            <form method="post" action="{{ route('timestamps-submit') }}" class="p-3">
                @csrf
                <p id="time"></p>
                <!-- Stamp Type -->
                <label class="form-label">業務を始めるか、終わるかを選んでください</label>
                <div class="mb-3">
                    <div class="form-check form-check-inline">
                        <input required type="radio" id="in" name="stamp_type" value="in" class="form-check-input">
                        <label for="in" class="form-check-label">開始</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input required type="radio" id="out" name="stamp_type" value="out" class="form-check-input">
                        <label for="out" class="form-check-label">終了</label>
                    </div>
                </div>
            
                <!-- Description -->
                <div class="mb-3">
                    <label for="description" class="form-label">どんな業務を行うか宣言してください(終了時は登録されないので不要です)</label>
                    <input type="text" id="description" name="description" class="form-control">
                </div>
            
                <!-- Submit Button -->
                <div class="mb-3">
                    <button type="submit" class="btn btn-primary">打刻</button>
                </div>

                {{-- スプレッドシートのURLを指定する(クエリパラメータでも可能) --}}
                <div class="mb-3">
                    <label for="spreadsheet_url" class="form-label">SpreadsheetのURLを入力してください。</label>
                    <input type="text" name="spreadsheet_url" value="{{ $url }}" class="form-control" id="spreadsheet_url">
                    <button class="btn btn-primary" id="to_spreadsheet">Spreadsheetを確認する</button>
                </div>
            </form>
            
            <table class="table table-bordered table-hover table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>日時</th>
                        <th>開始終了の種別</th>
                        <th>業務内容</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($timestamps as $timestamp)
                    <tr>
                        <td>{{ $timestamp->created_at }}</td>
                        <td>{{ $timestamp->stamp_type === 'in' ? '開始' : '終了' }}</td>
                        <td>{{ $timestamp->description }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        
        <script>
            (()=>{
                const updateTime = () => {
                    const now = new Date();
                    const days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                    const dayName = days[now.getDay()];
                    const dateString = now.toLocaleDateString();
                    const timeString = now.toLocaleTimeString();

                    document.getElementById('time').textContent = `${dayName}, ${dateString}, ${timeString}`;
                }

                // ページ読み込み時に時刻を表示
                updateTime();

                // 毎秒更新
                setInterval(updateTime, 1000);
            })();
            (()=>{
                const toSpreadsheet = () => {
                    const url = document.getElementById('spreadsheet_url').value;
                    window.open(url);
                }

                document.getElementById('to_spreadsheet').addEventListener('click', toSpreadsheet);
            })();
        </script>

        <style>
            .inner{
                width: 100%;
                max-width: 920px;
                margin: 0 auto;
            }
        </style>
    </body>

</html>
