namespace php Xin.Thrift.MonitorService

struct BaiduTieba {
    1: i32 id,
    2: i64 fid,
    3: string nickname
    4: string name
    5: string avatar
    6: i8 levelId
    7: string levelName
    8: i32 levelupScore
    9: string slogan
    10: i32 curScore
}