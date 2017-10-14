namespace php GithubService

struct CommitsLog {
    1: i64 id,
    2: string username,
    3: i32 commits
}

service Github {
    bool receivedEvents(string token)
    bool commits(string committer, string token)
    list<CommitsLog> commitsLog(string committer)
}