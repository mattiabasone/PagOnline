<?php

namespace PagOnline;

final class Actions
{
    // Init
    public const IGFS_CG_INIT = Init\IgfsCgInit::class;
    public const IGFS_CG_SELECTOR = Init\IgfsCgSelector::class;
    public const IGFS_CG_VERIFY = Init\IgfsCgVerify::class;

    // Mpi
    public const IGFS_CG_MPI_AUTH = Mpi\IgfsCgMpiAuth::class;
    public const IGFS_CG_MPI_ENROLL = Mpi\IgfsCgMpiEnroll::class;

    // PayByMail
    public const IGFS_CG_PAY_BY_MAIL_INIT = PayByMail\IgfsCgPayByMailInit::class;
    public const IGFS_CG_PAY_BY_MAIL_VERIFY = PayByMail\IgfsCgPayByMailVerify::class;

    // Tokenizer
    public const IGFS_CG_TOKENIZER_CHECK = Tokenizer\IgfsCgTokenizerCheck::class;
    public const IGFS_CG_TOKENIZER_DELETE = Tokenizer\IgfsCgTokenizerDelete::class;
    public const IGFS_CG_TOKENIZER_ENROLL = Tokenizer\IgfsCgTokenizerEnroll::class;

    // Tran
    public const IGFS_CG_AUTH = Tran\IgfsCgAuth::class;
    public const IGFS_CG_CONFIRM = Tran\IgfsCgConfirm::class;
    public const IGFS_CG_CREDIT = Tran\IgfsCgCredit::class;
    public const IGFS_CG_VOID_AUTH = Tran\IgfsCgVoidAuth::class;
}
