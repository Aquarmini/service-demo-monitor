namespace php Xin.Thrift.GithubService

include "github/commits.thrift"

service Github {
    bool receivedEvents(string token)
    bool commits(string committer, string token)
    list<commits.CommitsLog> commitsLog(string committer, i32 btime, i32 etime)
}