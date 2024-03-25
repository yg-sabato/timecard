<script setup>
    import { ref } from 'vue';
    const props = defineProps({
        updateTmpDescription: Function,
    });
    const inputUrl = ref('');
    const isLoading = ref(false);
    const githubData = ref([]);

    props.updateTmpDescription('');

    const fetchGithubData = async () => {
        isLoading.value = true;
        githubData.value = [];

        const token = import.meta.env.VITE_GITHUB_ACCESS_TOKEN;
        const headers = {
            'Authorization': `token ${token}`
        };

        // URLから必要な情報を抽出
        const regex = /github\.com\/(.+)\/(.+)\/(issues|pull)\/(\d+)/;
        const match = inputUrl.value.match(regex);

        if (!match) {
            isLoading.value = false;
            throw new Error('Invalid GitHub URL');
        }

        // 抽出した情報を変数に代入
        const [, owner, repo, type, number] = match;
        
        // IssueまたはPull Requestのエンドポイントを構築
        const apiURL = `https://api.github.com/repos/${owner}/${repo}/${type === 'issues' ? 'issues' : 'pulls'}/${number}`;

        // APIリクエストを送信
        const response = await fetch(apiURL, { headers });
        if (!response.ok) {
            isLoading.value = false;
            throw new Error('Failed to fetch GitHub data');
        }

        // レスポンスデータをJSON形式で取得
        const data = await response.json();

        // リポジトリ名を取得
        const repoName = data.repository_url.split('/').pop();

        // 必要な情報を抽出
        githubData.value.push({
            title: data.title,
            body: data.body,
            url: data.html_url,
            repoName: repoName,
            type: type,
            number: number,
        });

        isLoading.value = false;

        // 入力欄をクリア
        inputUrl.value = '';

        // 記録の入力を追加
        props.updateTmpDescription(`${githubData.value[0].repoName}: ${githubData.value[0].title} #${githubData.value[0].number}`);
    };

</script>

<template>
    <h2><i class="fa-brands fa-github"></i> Github連携</h2>
    <VaProgressBar v-show="isLoading" indeterminate />
    <VaInput
        label="issueのURLを入力"
        v-model="inputUrl"
    ></VaInput>
    <VaButton @click="fetchGithubData">データを取得</VaButton>
    <div v-if="githubData.length > 0" class="cards">
        <a v-for="githubDatum in githubData" :href="githubDatum['url']" target="_blank" rel="noopener noreferrer">
            <VaCard>
                <VaCardTitle><i class="fa-brands fa-github"></i> {{ githubDatum['repoName'] }}</VaCardTitle>
                <VaCardContent>
                    <h3>{{ githubDatum['title'] }}</h3>
                    <p>{{ githubDatum['type'] }} #{{ githubDatum['number'] }}</p>
                    <p>{{ githubDatum['body'] }}</p>
                </VaCardContent>
            </VaCard>
        </a>
    </div>
</template>

<style lang="scss">
    .va-input {
        width: 100%;
    }
    .va-button {
        max-width: 300px;
        width: 100%;
    }
    .cards {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
    }
    .va-card {
        width: 100%;
        max-width: 480px;
    }
    h3{
        margin-top: 0;
        margin-bottom: 1rem;
        font-size: 1.2rem;
    }
</style>