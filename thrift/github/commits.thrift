namespace php Xin.Thrift.MonitorService

struct CommitsLog {
    1: i64 id,
    2: string username,
    3: i32 commits
}