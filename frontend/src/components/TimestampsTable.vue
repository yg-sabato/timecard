<script setup>
    import { ref } from 'vue';
    const timestamps = ref([]);
    const inFlag = ref(false);
    const hourlyWage = ref(0);
    const totalHours = ref(0);
    const isLoading = ref(false);

    const fetchTimestamps = async () => {
        isLoading.value = true;
        const response = await fetch(`${import.meta.env.VITE_BACKEND_ENDPOINT}/api/get-this-month/`);
        const data = await response.json();
        timestamps.value = data['timestamps'];
        inFlag.value = data['inFlag'];
        hourlyWage.value = data['hourlyWage'];
        totalHours.value = data['totalTime'];
        isLoading.value = false;
    };

    const convertUTCtoJST = (utcDateString) => {
        // UTCの日時をDateオブジェクトとして解析
        const utcDate = new Date(utcDateString);

        // 日本時間（JST）に変換（UTC+9時間）
        // getTimezoneOffset()はミリ秒単位でのオフセットを返すため、
        // 60で割って分単位にし、さらに9時間分（540分）を足す
        const jstDate = new Date(utcDate.getTime() + (utcDate.getTimezoneOffset() * 60 * 1000) + (9 * 60 * 60 * 1000));

        // 日本時間をYYYY-MM-DD HH:MM:SS形式にフォーマット
        const month = String(jstDate.getMonth() + 1).padStart(2, '0'); // 月は0から始まるため+1
        const day = String(jstDate.getDate()).padStart(2, '0');
        const hours = String(jstDate.getHours()).padStart(2, '0');
        const minutes = String(jstDate.getMinutes()).padStart(2, '0');

        return `${month}/${day} ${hours}:${minutes}`;
    };

    const formatWithCommas = (number) => {
        // 数値を固定小数点表記に変換してから文字列にする
        const fixedNumber = number.toFixed(0);
        
        // 正規表現を使用して3桁ごとにカンマを挿入
        // \Bは単語の境界以外の位置にマッチし、(?=(\d{3})+(?!\d))は3桁ごとの位置を見つける
        return fixedNumber.replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    };

    fetchTimestamps();
</script>

<template>
    <h2>打刻一覧</h2>
    <VaProgressBar v-show="isLoading" indeterminate />
    <p>合計時間: {{ totalHours.toFixed(1) }}h</p>
    <p>現在の金額: ¥{{ formatWithCommas(Math.trunc(hourlyWage * totalHours)) }}</p>
    <table class="va-table">
        <thead>
            <tr>
                <th>日時</th>
                <th>種別</th>
                <th>業務内容</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="timestamp in timestamps" :key="timestamp.id">
                <td>{{ convertUTCtoJST(timestamp['created_at']) }}</td>
                <td>{{ timestamp['stamp_type'] }}</td>
                <td>{{ timestamp['description'] }}</td>
            </tr>
        </tbody>
    </table>
</template>

<style lang="scss">
    table{
        width: 100%;
    }
</style>